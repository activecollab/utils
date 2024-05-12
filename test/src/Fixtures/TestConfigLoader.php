<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Utils\Test\Fixtures;

use ActiveCollab\ConfigLoader\ConfigLoader;
use ActiveCollab\ConfigLoader\Exception\ValidationException;

class TestConfigLoader extends ConfigLoader
{
    public function callOnLoad(): void
    {
        $this->onLoad();
    }

    public function callOnValidationFailed(ValidationException $e): void
    {
        $this->onValidationFailed($e);
    }

    public function hasValue(string $option_name): bool
    {
        return false;
    }

    public function getValue(string $option_name, $default = null)
    {
        return $default;
    }
}
