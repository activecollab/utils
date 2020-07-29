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

class PresentValidatorTest extends TestCase
{
    private $config_array_path;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config_array_path = dirname(dirname(__DIR__)) . '/resources/config_array.php';
    }

    public function testPresenceCantBeRequiredAfterLoad()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Options can be required only before they are loaded.");

        (new ArrayConfigLoader($this->config_array_path))
            ->load()
            ->requirePresence('EMPTY_VALUE');
    }

    public function testPresentAlertsWhenOptionNotPresent()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Found config options do not match configured requirements.");

        (new ArrayConfigLoader($this->config_array_path))
            ->requirePresence('OF OPTION THAT DOES NOT EXIST')
            ->load();
    }

    public function testPresentDoesNotAlertWhenOptionValueIsEmpty()
    {
        $config_loader = (new ArrayConfigLoader($this->config_array_path))
            ->requirePresence('EMPTY_VALUE')
            ->load();

        $this->assertSame('', $config_loader->getValue('EMPTY_VALUE'));
    }
}
