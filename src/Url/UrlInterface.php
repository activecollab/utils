<?php

/*
 * This file is part of the Feud project.
 *
 * (c) PhpCloud.org Core Team <core@phpcloud.org>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Url;

interface UrlInterface
{
    public function getUrl(): string;
    public function getExtendedUrl(array $extendWith): string;
    public function removeQueryElement(string $queryElementName): string;
}
