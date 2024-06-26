<?php

declare(strict_types=1);

namespace ActiveCollab\Utils\Test;

use ActiveCollab\ClassFinder\ClassDir\ClassDir;
use ActiveCollab\ClassFinder\ClassFinder;
use ActiveCollab\Utils\Test\Base\TestCase;
use ActiveCollab\Utils\Test\Resources\DirToScan\LoadableClass;
use ReflectionClass;

class ClassFinderTest extends TestCase
{
    private string $dir_path;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->dir_path = __DIR__ . '/Resources/DirToScan';
    }

    public function testWillFindClasses(): void
    {
        $classes = (new ClassFinder())->scanDirForClasses(
            new ClassDir($this->dir_path, 'ActiveCollab\\Utils\\Test\\Resources\\DirToScan', LoadableClass::class),
        );

        $this->assertCount(2, $classes);

        $loadable_class_path = sprintf('%s/%s', $this->dir_path, 'LoadableClass.php');
        $abstract_loadable_class_path = sprintf('%s/%s', $this->dir_path, 'AbstractLoadableClass.php');

        $this->assertArrayHasKey($loadable_class_path, $classes);
        $this->assertArrayHasKey($abstract_loadable_class_path, $classes);

        $this->assertInstanceOf(ReflectionClass::class, $classes[$loadable_class_path]);
        $this->assertSame(
            $loadable_class_path,
            $classes[$loadable_class_path]->getFileName(),
        );

        $this->assertInstanceOf(ReflectionClass::class, $classes[$abstract_loadable_class_path]);
        $this->assertSame(
            $abstract_loadable_class_path,
            $classes[$abstract_loadable_class_path]->getFileName(),
        );
    }

    public function testCanSkipAbstractClasses(): void
    {
        $classes = (new ClassFinder())->scanDirForClasses(
            new ClassDir($this->dir_path, 'ActiveCollab\\Utils\\Test\\Resources\\DirToScan', LoadableClass::class),
            true,
        );

        $this->assertCount(1, $classes);

        $loadable_class_path = sprintf('%s/%s', $this->dir_path, 'LoadableClass.php');
        $abstract_loadable_class_path = sprintf('%s/%s', $this->dir_path, 'AbstractLoadableClass.php');

        $this->assertArrayHasKey($loadable_class_path, $classes);
        $this->assertArrayNotHasKey($abstract_loadable_class_path, $classes);
    }
}