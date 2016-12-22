<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\Utils\Test\ConfigLoader;

use ActiveCollab\ConfigLoader\ArrayConfigLoader;
use ActiveCollab\Utils\Test\Base\TestCase;

class RequiredValidatorTest extends TestCase
{
    private $config_array_path;

    public function setUp()
    {
        parent::setUp();

        $this->config_array_path = dirname(__DIR__, 2) . '/resources/config_array.php';
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Option values can be required only before they are loaded.
     */
    public function testRequireValueCanBeRequiredPriorToLoad()
    {
        (new ArrayConfigLoader($this->config_array_path))
            ->load()
            ->requireValue('EMPTY_VALUE');
    }

    /**
     * @expectedException \ActiveCollab\ConfigLoader\Exception\ValidationException
     * @expectedExceptionMessage Found config options do not match configured requirements.
     */
    public function testRequireValueAlertsWhenOptionNotPresent()
    {
        (new ArrayConfigLoader($this->config_array_path))
            ->requireValue('OF OPTION THAT DOES NOT EXIST')
            ->load();
    }

    /**
     * @expectedException \ActiveCollab\ConfigLoader\Exception\ValidationException
     * @expectedExceptionMessage Found config options do not match configured requirements.
     */
    public function testRequireValueAlertsWhenOptionValueIsEmpty()
    {
        (new ArrayConfigLoader($this->config_array_path))
            ->requireValue('EMPTY_VALUE')
            ->load();
    }

    public function testPresentDoesNotAlertWhenNonEmptyValueIsFound()
    {
        $config_loader = (new ArrayConfigLoader($this->config_array_path))
            ->requireValue('MYSQL_PORT')
            ->load();

        $this->assertSame(3306, $config_loader->getValue('MYSQL_PORT'));
    }
}
