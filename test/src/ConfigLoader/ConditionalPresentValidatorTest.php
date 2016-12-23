<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\Utils\Test\ConfigLoader;

use ActiveCollab\ConfigLoader\ArrayConfigLoader;
use ActiveCollab\Utils\Test\Base\TestCase;

class ConditionalPresentValidatorTest extends TestCase
{
    private $config_array_path;

    public function setUp()
    {
        parent::setUp();

        $this->config_array_path = dirname(dirname(__DIR__)) . '/resources/config_array.php';
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Options can be required only before they are loaded.
     */
    public function testPresenceCantBeRequiredAfterLoad()
    {
        (new ArrayConfigLoader($this->config_array_path))
            ->load()
            ->requirePresenceWhen('EMPTY_VALUE', '', 'NEW VALUE TO REQUIRE');
    }

    /**
     * @expectedException \ActiveCollab\ConfigLoader\Exception\ValidationException
     * @expectedExceptionMessage Found config options do not match configured requirements.
     */
    public function testConditionalPresenceRequiresConditionOpion()
    {
        (new ArrayConfigLoader($this->config_array_path))
            ->requirePresenceWhen('OPTION THAT DOES NOT EXIST', '', 'MYSQL_HOST')
            ->load();
    }

    /**
     * @expectedException \ActiveCollab\ConfigLoader\Exception\ValidationException
     * @expectedExceptionMessage Found config options do not match configured requirements.
     */
    public function testConditionalPresenceRequiresConditionOpionValue()
    {
        (new ArrayConfigLoader($this->config_array_path))
            ->requirePresenceWhen('EMPTY_VALUE', '', 'MYSQL_HOST')
            ->load();
    }

    /**
     * @expectedException \ActiveCollab\ConfigLoader\Exception\ValidationException
     * @expectedExceptionMessage Found config options do not match configured requirements.
     */
    public function testPresentAlertsWhenOptionNotPresent()
    {
        (new ArrayConfigLoader($this->config_array_path))
            ->requirePresenceWhen('MYSQL_HOST', 'localhost', 'OPTION THAT DOES NOT EXIST')
            ->load();
    }

    public function testPresentDoesNotAlertWhenOptionValueIsEmpty()
    {
        $config_loader = (new ArrayConfigLoader($this->config_array_path))
            ->requirePresenceWhen('MYSQL_HOST', 'localhost', 'EMPTY_VALUE')
            ->load();

        $this->assertSame('', $config_loader->getValue('EMPTY_VALUE'));
    }
}
