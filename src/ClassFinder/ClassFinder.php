<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\ClassFinder;

use ActiveCollab\ClassFinder\ClassDir\ClassDirInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

class ClassFinder implements ClassFinderInterface
{
    public function scanDirsForInstances(
        array $class_dirs,
        callable $with_found_instance,
        array $constructor_arguments = []
    ): void
    {
        foreach ($class_dirs as $class_dir) {
            $this->scanDirForInstances($class_dir, $with_found_instance, $constructor_arguments);
        }
    }

    public function scanDirForInstances(
        ClassDirInterface $class_dir,
        callable $with_found_instance,
        array $constructor_arguments = []
    ): void
    {
        foreach ($this->scanDirForClasses($class_dir, true, true) as $reflection_class) {
            $found_instance = $reflection_class->newInstanceArgs($constructor_arguments);
            call_user_func($with_found_instance, $found_instance);
        }
    }

    public function scanDirForClasses(
        ClassDirInterface $class_dir,
        bool $skip_abstract = false,
        bool $skip_non_descendant = false
    ): array
    {
        $dir_path = rtrim($class_dir->getPath(), '/');

        if (!is_dir($dir_path)) {
            return [];
        }

        $dir_path_len = strlen($dir_path);
        $instance_namespace = rtrim($class_dir->getNamespace(), '\\');

        $result = [];

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir_path), RecursiveIteratorIterator::SELF_FIRST) as $file) {
            if ($file->isFile() && $file->getExtension() == 'php') {
                $class_name = $instance_namespace . '\\' . implode('\\', explode('/', substr($file->getPath() . '/' . $file->getBasename('.php'), $dir_path_len + 1)));

                $reflection = new ReflectionClass($class_name);

                if ($skip_abstract && $reflection->isAbstract()) {
                    continue;
                }

                if ($skip_non_descendant && !$reflection->isSubclassOf($class_dir->getInstanceClass())) {
                    continue;
                }

                $result[$file->getPathname()] = $reflection;
            }
        }

        return $result;
    }
}
