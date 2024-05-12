<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\Utils\Test\ConfigLoader;

use ActiveCollab\ConfigLoader\DotEnvConfigLoader;
use ActiveCollab\Utils\Test\Base\TestCase;
use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;

class DotenvConfigLoaderTest extends TestCase
{
    private string $dotenv_dir_path;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dotenv_dir_path = dirname(__DIR__) . '/Resources/Dotenv';
    }

    /**
     * @dataProvider hasValueDataProvider
     */
    public function testHasValue(
        string $config_option,
        bool $should_be_found,
    ): void
    {
        $dotenv = $this->createDotenv($this->dotenv_dir_path);

        $has_value = (new DotEnvConfigLoader($dotenv))
            ->load()
            ->hasValue($config_option);

        if ($should_be_found) {
            $this->assertTrue($has_value);
        } else {
            $this->assertFalse($has_value);
        }
    }

    public function hasValueDataProvider(): array
    {
        return [
            ['CONFIG_OPTION', true],
            ['Config_Option', true],
            ['config_option', true],
            ['NOT_FOUND', false],
            ['Not_Fount', false],
            ['not_found', false],
        ];
    }

    /**
     * @dataProvider getValueDataProvider
     */
    public function testGetValue(
        string $config_option,
        ?string $default_value,
        ?string $expected_value,
    ): void
    {
        $dotenv = $this->createDotenv($this->dotenv_dir_path);

        $value = (new DotEnvConfigLoader($dotenv))
            ->load()
            ->getValue($config_option, $default_value);

        $this->assertSame($expected_value, $value);
    }

    public function getValueDataProvider(): array
    {
        return [
            ['CONFIG_OPTION', null, 'Value'],
            ['Config_Option', null, 'Value'],
            ['config_option', null, 'Value'],
            ['NOT_FOUND', null, null],
            ['Not_Fount', null, null],
            ['not_found', null, null],
            ['return_different_default', 'different-default', 'different-default'],
        ];
    }

    private function createDotenv(string $dir_path): Dotenv
    {
        return Dotenv::create(
            RepositoryBuilder::createWithNoAdapters()
                ->addAdapter(PutenvAdapter::class)
                ->make(),
            $dir_path,
        );
    }
}
