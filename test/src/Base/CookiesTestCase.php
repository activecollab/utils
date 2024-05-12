<?php

/*
 * This file is part of the Active Collab Cookies project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Utils\Test\Base;

use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class CookiesTestCase extends TestCase
{
    protected ServerRequestInterface|RequestInterface $request;
    protected ResponseInterface $response;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = (new ServerRequestFactory())->createServerRequest(
            'GET',
            'https://example.com:443/foo/bar?abc=123'
        );
        $this->response = (new ResponseFactory())->createResponse();
    }
}
