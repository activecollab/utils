<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\HttpClient\RequestMiddleware;

use Psr\Http\Message\RequestInterface;

interface RequestMiddlewareInterface
{
    public function alter(RequestInterface $request): RequestInterface;
}
