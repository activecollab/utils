<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\HttpClient\Client\Curl;

use ActiveCollab\HttpClient\Client\ClientInterface;

class CurlClient implements ClientInterface, CurlClientInterface
{
    /**
     * @var resource|null
     */
    private $handle;

    public function __destruct()
    {
        if (is_resource($this->handle)) {
            curl_close($this->handle);
        }
    }

    public function setOption(int $option, $value): ClientInterface
    {
    }
}
