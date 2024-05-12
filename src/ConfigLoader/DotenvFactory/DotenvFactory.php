<?php

declare(strict_types=1);

namespace ActiveCollab\ConfigLoader\DotenvFactory;

use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;

class DotenvFactory implements DotenvFactoryInterface
{
    public function createDotenv(
        string $path,
        string $name = null,
    ): Dotenv
    {
        return Dotenv::create(
            RepositoryBuilder::createWithNoAdapters()
                ->addAdapter(PutenvAdapter::class)
                ->make(),
            $path,
            $name,
        );
    }
}
