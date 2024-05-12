<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\ClassFinder;

use ActiveCollab\ClassFinder\ClassDir\ClassDirInterface;

interface ClassFinderInterface
{
    public function scanDirsForInstances(
        array $class_dirs,
        callable $with_found_instance,
        array $constructor_arguments = []
    ): void;

    public function scanDirForInstances(
        ClassDirInterface $class_dir,
        callable $with_found_instance,
        array $constructor_arguments = []
    ): void;

    public function scanDirForClasses(
        ClassDirInterface $class_dir,
        bool $skip_abstract = false,
        bool $skip_non_descendant = false,
    ): array;
}
