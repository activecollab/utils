<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\ClassFinder\ClassDir;

readonly class ClassDir implements ClassDirInterface
{
    public function __construct(
        private string $path,
        private string $namespace,
        private string $instance_class,
    )
    {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getInstanceClass(): string
    {
        return $this->instance_class;
    }
}
