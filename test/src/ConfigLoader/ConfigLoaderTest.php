<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\Utils\Test\ConfigLoader;

use ActiveCollab\ConfigLoader\ArrayConfigLoader;
use ActiveCollab\Utils\Test\Base\TestCase;

class ConfigLoaderTest extends TestCase
{
    private $config_array_path;
    private $not_an_array_path;

    public function setUp()
    {
        parent::setUp();

        $this->config_array_path = dirname(dirname(__DIR__)) . '/resources/config_array.php';
        $this->not_an_array_path = dirname(dirname(__DIR__)) . '/resources/not_an_array.php';
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Options file does not exist.
     */
    public function testExceptionOnInvalidFilePath()
    {
        $this->assertFileNotExists('/not-a-file');
        new ArrayConfigLoader('/not-a-file');
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Options not loaded.
     */
    public function testExceptionOnPresenceCheckWhenOptionsAreNotLoaded()
    {
        (new ArrayConfigLoader($this->config_array_path))->hasValue('EMPTY_VALUE');
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Options not loaded.
     */
    public function testExceptionOnGetWhenOptionsAreNotLoaded()
    {
        (new ArrayConfigLoader($this->config_array_path))->getValue('EMPTY_VALUE');
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Options already loaded.
     */
    public function testExceptionOnMultipleLoadAttempts()
    {
        (new ArrayConfigLoader($this->config_array_path))
            ->load()
            ->load();
    }

    public function testLoadOverridesNonArrayReturningFiles()
    {
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
}
