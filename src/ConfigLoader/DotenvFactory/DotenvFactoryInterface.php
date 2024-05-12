<?php

declare(strict_types=1);

namespace ActiveCollab\ConfigLoader\DotenvFactory;

use Dotenv\Dotenv;

interface DotenvFactoryInterface
{
    public function createDotenv(
        string $path,
        string $name = null,
    ): Dotenv;
}
