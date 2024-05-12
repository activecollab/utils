<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Url;

class Url implements UrlInterface
{
    private string $url;
    private ?array $parsedUrl = null;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getExtendedUrl(array $extendWith): string
    {
        if (empty($extendWith)) {
            return $this->url;
        }

        $parsed_url = $this->getParsedUrl();

        return sprintf(
            '%s://%s%s%s%s%s%s',
            $parsed_url['scheme'],
            !empty($parsed_url['user']) && array_key_exists('pass', $parsed_url)
                ? sprintf('%s:%s@', $parsed_url['user'], $parsed_url['pass'])
                : '',
            $parsed_url['host'],
            !empty($parsed_url['port']) ? sprintf(':%d', $parsed_url['port']) : '',
            !empty($parsed_url['path']) ? '/' . ltrim($parsed_url['path'], '/') : '',
            $this->prepareQueryString($parsed_url, $extendWith),
            !empty($parsed_url['fragment']) ? sprintf('#%s', $parsed_url['fragment']) : '',
        );
    }

    public function removeQueryElement(string $queryElementName): string
    {
        if (empty($queryElementName)) {
            return $this->url;
        }

        $parsedUrl = $this->getParsedUrl();
        $query = [];

        if (!empty($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $query);
            if (isset($query[$queryElementName])) {
                unset($query[$queryElementName]);
            }
        }

        return sprintf(
            '%s%s%s%s%s%s%s',
            !empty($parsedUrl['scheme']) ? sprintf('%s://', $parsedUrl['scheme']) : '',
            !empty($parsedUrl['user']) && array_key_exists('pass', $parsedUrl)
                ? sprintf('%s:%s@', $parsedUrl['user'], $parsedUrl['pass'])
                : '',
            !empty($parsedUrl['host']) ? $parsedUrl['host'] : '',
            !empty($parsedUrl['port']) ? sprintf(':%d', $parsedUrl['port']) : '',
            !empty($parsedUrl['path']) ? '/' . ltrim($parsedUrl['path'], '/') : '',
            !empty($query) ? '?' . http_build_query($query) : '',
            !empty($parsedUrl['fragment']) ? sprintf('#%s', $parsedUrl['fragment']) : '',
        );
    }

    private function prepareQueryString(array $parsedUrl, array $extendWith): string
    {
        if (!empty($parsedUrl['query'])) {
            $query = [];
            parse_str($parsedUrl['query'], $query);

            $query = array_merge($query, $extendWith);
        } else {
            $query = $extendWith;
        }

        return '?' . http_build_query($query);
    }

    public function __toString(): string
    {
        return $this->url;
    }

    protected function getParsedUrl(): array
    {
        if (empty($this->parsedUrl)) {
            $this->parsedUrl = parse_url($this->getUrl());

            if (!is_array($this->parsedUrl)) {
                $this->parsedUrl = [];
            }
        }

        return $this->parsedUrl;
    }
}
