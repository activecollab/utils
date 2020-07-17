<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\Utils\Test\Fixtures;

use ActiveCollab\ConfigLoader\ConfigLoader;
use ActiveCollab\ConfigLoader\Exception\ValidationException;

class TestConfigLoader extends ConfigLoader
{
    public function callOnLoad()
    {
        $this->onLoad();
    }

    public function callOnValidationFailed(ValidationException $e)
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
