<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Utils\Test\HttpClient;

use ActiveCollab\HttpClient\Client\ClientInterface;
use ActiveCollab\HttpClient\Client\Factory\ClientFactoryInterface;
use ActiveCollab\HttpClient\HttpClient;
use ActiveCollab\HttpClient\Configure\RequestMiddleware\RequestMiddlewareInterface;
use ActiveCollab\Utils\Test\Base\TestCase;
use Laminas\Diactoros\RequestFactory;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClientTest extends TestCase
{
    /**
     * @dataProvider provideDataForMiddlewareInvocation
     */
    public function testWillInvokeMiddlewares(
        string $method,
        string $url,
    ): void
    {
        $psrHttpClient = $this->createMock(ClientInterface::class);
        $psrHttpClient
            ->expects($this->once())
            ->method('sendRequest');

        $clientFactoryMock = $this->createMock(ClientFactoryInterface::class);
        $clientFactoryMock
            ->expects($this->once())
            ->method('createClient')
            ->willReturn($psrHttpClient);

        $requestMock = $this->createMock(RequestInterface::class);

        $requestFactoryMock = $this->createMock(RequestFactoryInterface::class);
        $requestFactoryMock
            ->expects($this->once())
            ->method('createRequest')
            ->willReturn($requestMock);

        $middleware1 = $this->createMock(RequestMiddlewareInterface::class);
        $middleware1
            ->expects($this->once())
            ->method('alter')
            ->willReturn($requestMock);

        $middleware2 = $this->createMock(RequestMiddlewareInterface::class);
        $middleware2
            ->expects($this->once())
            ->method('alter')
            ->willReturn($requestMock);

        $httpClient = new HttpClient($clientFactoryMock, $requestFactoryMock);
        call_user_func([$httpClient, $method], $url, $middleware1, $middleware2);
    }

    public function provideDataForMiddlewareInvocation(): array
    {
        return [
            ['get', 'https://activecollab.com'],
            ['post', 'https://activecollab.com'],
            ['put', 'https://activecollab.com'],
            ['patch', 'https://activecollab.com'],
            ['delete', 'https://activecollab.com'],
        ];
    }

    /**
     * @dataProvider provideDataForMakeRequestTest
     */
    public function testWillSendRequest(
        string $method,
        string $expectedHttpMethod,
    ): void
    {
        $psrHttpClient = $this->getTestHttpClient();

        $clientFactoryMock = $this->createMock(ClientFactoryInterface::class);
        $clientFactoryMock
            ->expects($this->once())
            ->method('createClient')
            ->willReturn($psrHttpClient);

        (new HttpClient($clientFactoryMock, new RequestFactory()))->$method('https://activecollab.com?test=1');

        $capturedRequest = $psrHttpClient->getCapturedRequest();

        $this->assertInstanceOf(RequestInterface::class, $capturedRequest);
        $this->assertSame($expectedHttpMethod, $capturedRequest->getMethod());
        $this->assertSame('https://activecollab.com?test=1', (string) $capturedRequest->getUri());
    }

    public function provideDataForMakeRequestTest(): array
    {
        return [
            ['get', 'GET'],
            ['post', 'POST'],
            ['put', 'PUT'],
            ['patch', 'PATCH'],
            ['delete', 'DELETE'],
        ];
    }

    private function getTestHttpClient(ResponseInterface $response = null): ClientInterface
    {
        if (empty($response)) {
            $response = $this->createMock(ResponseInterface::class);
        }

        return new class ($response) implements ClientInterface
        {
            private ResponseInterface $response;
            private ?RequestInterface $capturedRequest = null;

            public function __construct(ResponseInterface $response)
            {
                $this->response = $response;
            }

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                $this->capturedRequest = $request;

                return $this->response;
            }

            public function getCapturedRequest(): ?RequestInterface
            {
                return $this->capturedRequest;
            }
        };
    }
}
