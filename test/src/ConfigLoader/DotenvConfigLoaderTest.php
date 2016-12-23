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

class DotenvConfigLoaderTest extends TestCase
{
    private $dotenv_dir_path;

    public function setUp()
    {
        parent::setUp();

        $this->dotenv_dir_path = dirname(__DIR__) . '/Resources/Dotenv';
    }

    /**
     * @dataProvider hasValueDataProvider
     * @param string $config_option
     * @param bool   $should_be_found
     */
    public function testHasValue($config_option, $should_be_found)
    {
        $has_value = (new DotEnvConfigLoader(new Dotenv($this->dotenv_dir_path)))
            ->load()
            ->hasValue($config_option);

        if ($should_be_found) {
            $this->assertTrue($has_value);
        } else {
            $this->assertFalse($has_value);
        }
    }

    public function hasValueDataProvider()
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
     * @param string $config_option
     * @param string $default_value
     * @param bool   $expected_value
     */
    public function testGetValue($config_option, $default_value, $expected_value)
    {
        $value = (new DotEnvConfigLoader(new Dotenv($this->dotenv_dir_path)))
            ->load()
            ->getValue($config_option, $default_value);

        $this->assertSame($expected_value, $value);
    }

    public function getValueDataProvider()
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
}
