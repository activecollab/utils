<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\Utils\Test\ConfigLoader;

use ActiveCollab\ConfigLoader\ArrayConfigLoader;
use ActiveCollab\Utils\Test\Base\TestCase;

class ConditionalValueRequiredValidatorTest extends TestCase
{
    private $config_array_path;

    public function setUp()
    {
        parent::setUp();

        $this->config_array_path = dirname(__DIR__, 2) . '/resources/config_array.php';
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Options can be required only before they are loaded.
     */
    public function testValueRequiredCantBeRequiredAfterLoad()
    {
        (new ArrayConfigLoader($this->config_array_path))
            ->load()
            ->requireValueWhen('EMPTY_VALUE', '', 'NEW VALUE TO REQUIRE');
    }

    /**
     * @expectedException \ActiveCollab\ConfigLoader\Exception\ValidationException
     * @expectedExceptionMessage Found config options do not match configured requirements.
     */
    public function testConditionalValueRequiredRequiresConditionOpion()
    {
        (new ArrayConfigLoader($this->config_array_path))
            ->requireValueWhen('OPTION THAT DOES NOT EXIST', '', 'MYSQL_HOST')
            ->load();
    }

    /**
     * @expectedException \ActiveCollab\ConfigLoader\Exception\ValidationException
     * @expectedExceptionMessage Found config options do not match configured requirements.
     */
    public function testConditionalValueRequiredRequiresConditionOpionValue()
    {
        (new ArrayConfigLoader($this->config_array_path))
            ->requireValueWhen('EMPTY_VALUE', '', 'MYSQL_HOST')
            ->load();
    }

    /**
     * @expectedException \ActiveCollab\ConfigLoader\Exception\ValidationException
     * @expectedExceptionMessage Found config options do not match configured requirements.
     */
    public function testConditionalValueRequiredAlertsWhenOptionNotPresent()
    {
        (new ArrayConfigLoader($this->config_array_path))
            ->requireValueWhen('MYSQL_HOST', 'localhost', 'OPTION THAT DOES NOT EXIST')
            ->load();
    }

    /**
     * @expectedException \ActiveCollab\ConfigLoader\Exception\ValidationException
     * @expectedExceptionMessage Found config options do not match configured requirements.
     */
    public function testConditionalValueRequiredAlertsWhenOptionValueIsEmpty()
    {
        (new ArrayConfigLoader($this->config_array_path))
            ->requireValueWhen('MYSQL_HOST', 'localhost', 'EMPTY_VALUE')
            ->load();
    }

    public function testConditionalValueRequiredDoesNotAlertWhenNonEmptyValueIsFound()
    {
        $config_loader = (new ArrayConfigLoader($this->config_array_path))
            ->requireValueWhen('MYSQL_HOST', 'localhost', 'MYSQL_PORT')
            ->load();

        $this->assertSame(3306, $config_loader->getValue('MYSQL_PORT'));
    }
}
