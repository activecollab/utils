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

    public function hasValue($option_name)
    {
        return false;
    }

    public function getValue($option_name, $default = null)
    {
        return $default;
    }
}
