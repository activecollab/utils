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
use LogicException;

class ConditionalValueRequiredValidatorTest extends TestCase
{
    private $config_array_path;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config_array_path = dirname(dirname(__DIR__)) . '/resources/config_array.php';
    }

    public function testValueRequiredCantBeRequiredAfterLoad()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Options can be required only before they are loaded.");

        (new ArrayConfigLoader($this->config_array_path))
            ->load()
            ->requireValueWhen('EMPTY_VALUE', '', 'NEW VALUE TO REQUIRE');
    }

    public function testConditionalValueRequiredRequiresConditionOpion()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Found config options do not match configured requirements.");

        (new ArrayConfigLoader($this->config_array_path))
            ->requireValueWhen('OPTION THAT DOES NOT EXIST', '', 'MYSQL_HOST')
            ->load();
    }

    public function testConditionalValueRequiredRequiresConditionOpionValue()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Found config options do not match configured requirements.");

        (new ArrayConfigLoader($this->config_array_path))
            ->requireValueWhen('EMPTY_VALUE', '', 'MYSQL_HOST')
            ->load();
    }

    public function testConditionalValueRequiredAlertsWhenOptionNotPresent()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Found config options do not match configured requirements.");

        (new ArrayConfigLoader($this->config_array_path))
            ->requireValueWhen('MYSQL_HOST', 'localhost', 'OPTION THAT DOES NOT EXIST')
            ->load();
    }

    public function testConditionalValueRequiredAlertsWhenOptionValueIsEmpty()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Found config options do not match configured requirements.");

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
