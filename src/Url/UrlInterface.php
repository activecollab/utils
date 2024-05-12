<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Url;

interface UrlInterface
{
    public function getUrl(): string;
    public function getExtendedUrl(array $extendWith): string;
    public function removeQueryElement(string $queryElementName): string;
}
