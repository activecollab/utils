<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\HttpClient;

use ActiveCollab\HttpClient\RequestMiddleware\RequestMiddlewareInterface;
use Psr\Http\Client\ClientInterface as PsrHttpClient;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClient implements HttpClientInterface
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    private PsrHttpClient $httpClient;
    private RequestFactoryInterface $requestFactory;

    public function __construct(PsrHttpClient $httpClient, RequestFactoryInterface $requestFactory)
    {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
    }

    public function get(string $url, RequestMiddlewareInterface ...$requestMiddlewares): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->prepareRequest(self::METHOD_GET, $url, ...$requestMiddlewares)
        );
    }

    public function post(string $url, RequestMiddlewareInterface ...$requestMiddlewares): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->prepareRequest(self::METHOD_POST, $url, ...$requestMiddlewares)
        );
    }

    public function put(string $url, RequestMiddlewareInterface ...$requestMiddlewares): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->prepareRequest(self::METHOD_PUT, $url, ...$requestMiddlewares)
        );
    }

    public function patch(string $url, RequestMiddlewareInterface ...$requestMiddlewares): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->prepareRequest(self::METHOD_PATCH, $url, ...$requestMiddlewares)
        );
    }

    public function delete(string $url, RequestMiddlewareInterface ...$requestMiddlewares): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->prepareRequest(self::METHOD_DELETE, $url, ...$requestMiddlewares)
        );
    }

    private function prepareRequest(
        string $httpMethod,
        string $url,
        RequestMiddlewareInterface ...$requestMiddlewares
    ): RequestInterface
    {
        $request = $this->requestFactory->createRequest($httpMethod, $url);

        foreach ($requestMiddlewares as $requestMiddleware) {
            $request = $requestMiddleware->alter($request);
        }

        return $request;
    }
}
