<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\ConfigLoader;

use ActiveCollab\ConfigLoader\Exception\ValidationException;
use LogicException;

abstract class ConfigLoader implements ConfigLoaderInterface
{
    private $is_loading = false;

    public function isLoading()
    {
        return $this->is_loading;
    }

    protected function &setIsLoading($value = true)
    {
        $this->is_loading = (bool) $value;

        return $this;
    }

    private $is_loaded = false;

    public function isLoaded()
    {
        return $this->is_loaded;
    }

    protected function &setIsLoaded($value = true)
    {
        $this->is_loaded = (bool) $value;

        return $this;
    }

    protected function canCheckValuePresence()
    {
        return $this->isLoading() || $this->isLoaded();
    }

    protected function canGetValue()
    {
        return $this->isLoading() || $this->isLoaded();
    }

    private $required_presence = [];

    public function &requirePresence(...$config_options)
    {
        if ($this->isLoaded()) {
            throw new LogicException('Options can be required only before they are loaded.');
        }

        $this->required_presence = array_unique(array_merge($this->required_presence, $config_options));

        return $this;
    }

    protected function &validate()
    {
        $exception = new ValidationException();

        foreach ($this->required_presence as $option_name) {
            if (!$this->hasValue($option_name)) {
                $exception->missing($option_name);
            }
        }

        if ($exception->hasErrors()) {
            throw $exception;
        }

        return $this;
    }

    protected function normalizeOptionName($option_name)
    {
        return mb_strtoupper($option_name);
    }
}
