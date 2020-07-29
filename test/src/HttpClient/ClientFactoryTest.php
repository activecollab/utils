<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Utils\Test\HttpClient;

use ActiveCollab\HttpClient\Client\Factory\ClientFactory;
use ActiveCollab\HttpClient\Client\Factory\ClientFactoryInterface;
use ActiveCollab\HttpClient\Client\ClientInterface;
use ActiveCollab\HttpClient\Configure\ClientMiddleware\ClientMiddlewareInterface;
use ActiveCollab\Utils\Test\Base\TestCase;
use Laminas\Diactoros\ResponseFactory;

class ClientFactoryTest extends TestCase
{
    public function testWillUseDefaultClientMiddlewares(): void
    {
        $clientMock = $this->createMock(ClientInterface::class);
        $middleware1 = $this->createMock(ClientMiddlewareInterface::class);
        $middleware1
            ->expects($this->once())
            ->method('alter');

        $middleware2 = $this->createMock(ClientMiddlewareInterface::class);
        $middleware2
            ->expects($this->once())
            ->method('alter');

        $clientFactory = $this->createTestFactory($clientMock, $middleware1, $middleware2);

        $clientFactory->createClient();
    }

    private function createTestFactory(
        ClientInterface $client,
        ClientMiddlewareInterface ...$defaultMiddlewares
    ): ClientFactoryInterface
    {
        return new class ($client, ...$defaultMiddlewares) extends ClientFactory
        {
            private ClientInterface $client;

            public function __construct(
                ClientInterface $client,
                ClientMiddlewareInterface ...$defaultMiddlewares
            )
            {
                parent::__construct(new ResponseFactory(), ...$defaultMiddlewares);

                $this->client = $client;
            }

            protected function doCreateClient(): ClientInterface
            {
                return $this->client;
            }
        };
    }
}
