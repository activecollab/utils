<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\HttpClient;

use ActiveCollab\HttpClient\Configure\ConfigureMiddlewareInterface;
use ActiveCollab\HttpClient\Configure\RequestMiddleware\RequestMiddlewareInterface;
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

    public function get(string $url, ConfigureMiddlewareInterface ...$configureMiddlewares): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->prepareRequest(self::METHOD_GET, $url, ...$configureMiddlewares)
        );
    }

    public function post(string $url, ConfigureMiddlewareInterface ...$configureMiddlewares): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->prepareRequest(self::METHOD_POST, $url, ...$configureMiddlewares)
        );
    }

    public function put(string $url, ConfigureMiddlewareInterface ...$configureMiddlewares): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->prepareRequest(self::METHOD_PUT, $url, ...$configureMiddlewares)
        );
    }

    public function patch(string $url, ConfigureMiddlewareInterface ...$configureMiddlewares): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->prepareRequest(self::METHOD_PATCH, $url, ...$configureMiddlewares)
        );
    }

    public function delete(string $url, ConfigureMiddlewareInterface ...$configureMiddlewares): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->prepareRequest(self::METHOD_DELETE, $url, ...$configureMiddlewares)
        );
    }

    private function prepareRequest(
        string $httpMethod,
        string $url,
        ConfigureMiddlewareInterface ...$configureMiddlewares
    ): RequestInterface
    {
        $request = $this->requestFactory->createRequest($httpMethod, $url);

        foreach ($configureMiddlewares as $middleware) {
            if ($middleware instanceof RequestMiddlewareInterface) {
                $request = $middleware->alter($request);
            }
        }

        return $request;
    }
}
