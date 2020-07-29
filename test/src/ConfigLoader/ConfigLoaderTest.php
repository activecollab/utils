<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Utils\Test\ConfigLoader;

use ActiveCollab\ConfigLoader\ArrayConfigLoader;
use ActiveCollab\ConfigLoader\Exception\ValidationException;
use ActiveCollab\Utils\Test\Base\TestCase;
use ActiveCollab\Utils\Test\Fixtures\TestConfigLoader;
use LogicException;

class ConfigLoaderTest extends TestCase
{
    private $config_array_path;
    private $not_an_array_path;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config_array_path = dirname(dirname(__DIR__)) . '/resources/config_array.php';
        $this->not_an_array_path = dirname(dirname(__DIR__)) . '/resources/not_an_array.php';
    }

    public function testExceptionOnInvalidFilePath()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Options file does not exist.");

        $this->assertFileDoesNotExist('/not-a-file');
        new ArrayConfigLoader('/not-a-file');
    }

    public function testExceptionOnPresenceCheckWhenOptionsAreNotLoaded()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Options not loaded.");

        (new ArrayConfigLoader($this->config_array_path))->hasValue('EMPTY_VALUE');
    }

    public function testExceptionOnGetWhenOptionsAreNotLoaded()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Options not loaded.");

        (new ArrayConfigLoader($this->config_array_path))->getValue('EMPTY_VALUE');
    }

    public function testExceptionOnMultipleLoadAttempts()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Options already loaded.");

        (new ArrayConfigLoader($this->config_array_path))
            ->load()
            ->load();
    }

    public function testLoadOverridesNonArrayReturningFiles()
    {
        $this->expectNotToPerformAssertions();

        (new ArrayConfigLoader($this->not_an_array_path))
            ->load();
    }

    public function testGetReturnsDefaultWhenOptionNotPresent()
    {
        $config_loader = (new ArrayConfigLoader($this->config_array_path))->load();

        $this->assertTrue($config_loader->hasValue('EMPTY_VALUE'));
        $this->assertSame('', $config_loader->getValue('EMPTY_VALUE', 1234567890));

        $this->assertFalse($config_loader->hasValue('THIS ONE NOT FOUND'));
        $this->assertSame(1234567890, $config_loader->getValue('THIS ONE NOT FOUND', 1234567890));
    }

    public function testExceptionOnDirectOnLoadCall()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("This method should be called only during loading.");

        (new TestConfigLoader())->callOnLoad();
    }

    public function testExceptionOnDirectOnValidationFailedCall()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("This method should be called only during loading.");

        (new TestConfigLoader())->callOnValidationFailed(new ValidationException());
    }
}
