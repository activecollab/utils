<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\ConfigLoader;

use ActiveCollab\ConfigLoader\Exception\ValidationException;
use Dotenv\Dotenv;
use LogicException;

class DotEnvConfigLoader extends ConfigLoader
{
    private $dotenv;

    public function __construct(Dotenv $dotenv)
    {
        $this->dotenv = $dotenv;
    }

    public function &load()
    {
        if ($this->isLoaded()) {
            throw new LogicException('Options already loaded.');
        }

        $this->setIsLoading(true);

        $this->dotenv->load();

        try {
            $this->validate();
        } catch (ValidationException $e) {
            throw $e;
        } finally {
            $this->setIsLoading(false);
        }

        $this->setIsLoaded(true);

        return $this;
    }

    public function hasValue($option_name)
    {
        if (!$this->canCheckValuePresence()) {
            throw new LogicException('Options not loaded.');
        }

        return getenv($this->normalizeOptionName($option_name)) !== false;
    }

    public function getValue($option_name, $default = null)
    {
        if (!$this->canGetValue()) {
            throw new LogicException('Options not loaded.');
        }

        $value = getenv($this->normalizeOptionName($option_name));

        return $value === false ? $default : $value;
    }
}
