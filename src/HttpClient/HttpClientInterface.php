<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\HttpClient;

use ActiveCollab\HttpClient\RequestMiddleware\RequestMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    public function get(string $url, RequestMiddlewareInterface ...$requestMiddlewares): ResponseInterface;
    public function post(string $url, RequestMiddlewareInterface ...$requestMiddlewares): ResponseInterface;
    public function put(string $url, RequestMiddlewareInterface ...$requestMiddlewares): ResponseInterface;
    public function patch(string $url, RequestMiddlewareInterface ...$requestMiddlewares): ResponseInterface;
    public function delete(string $url, RequestMiddlewareInterface ...$requestMiddlewares): ResponseInterface;
}
