<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\ClassFinder\ClassDir;

interface ClassDirInterface
{
    public function getPath(): string;
    public function getNamespace(): string;
    public function getInstanceClass(): string;
}
