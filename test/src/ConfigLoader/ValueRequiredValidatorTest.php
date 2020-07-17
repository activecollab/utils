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

class ValueRequiredValidatorTest extends TestCase
{
    private $config_array_path;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config_array_path = dirname(dirname(__DIR__)) . '/resources/config_array.php';
    }

    public function testRequireValueCantBeRequiredAfterLoad()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Option values can be required only before they are loaded.");

        (new ArrayConfigLoader($this->config_array_path))
            ->load()
            ->requireValue('EMPTY_VALUE');
    }

    public function testRequireValueAlertsWhenOptionNotPresent()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Found config options do not match configured requirements.");

        (new ArrayConfigLoader($this->config_array_path))
            ->requireValue('OF OPTION THAT DOES NOT EXIST')
            ->load();
    }

    public function testRequireValueAlertsWhenOptionValueIsEmpty()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Found config options do not match configured requirements.");

        (new ArrayConfigLoader($this->config_array_path))
            ->requireValue('EMPTY_VALUE')
            ->load();
    }

    public function testValueRequiredDoesNotAlertWhenNonEmptyValueIsFound()
    {
        $config_loader = (new ArrayConfigLoader($this->config_array_path))
            ->requireValue('MYSQL_PORT')
            ->load();

        $this->assertSame(3306, $config_loader->getValue('MYSQL_PORT'));
    }
}
