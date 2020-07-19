<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\HttpClient\RequestMiddleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

class JsonPayload implements RequestMiddlewareInterface
{
    private $payload;
    private StreamFactoryInterface $streamFactory;

    public function __construct($payload, StreamFactoryInterface $streamFactory)
    {
        $this->payload = $payload;
        $this->streamFactory = $streamFactory;
    }

    public function alter(RequestInterface $request): RequestInterface
    {
        return $request
            ->withHeader('Content-Type', 'application/json')
            ->withBody(
                $this->streamFactory->createStream(
                    json_encode(
                        $this->payload,
                        JSON_PRESERVE_ZERO_FRACTION | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                    )
                )
            );
    }
}
