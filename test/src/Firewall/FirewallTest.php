<?php

/*
 * This file is part of the Active Collab project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\Utils\Test\Firewall;

use ActiveCollab\Utils\Test\Base\TestCase;
use ActiveCollab\Firewall\Firewall;
use ActiveCollab\Firewall\IpAddress;

/**
 * @package ActiveCollab.tests.authentication
 */
class FirewallTest extends TestCase
{
    /**
     * @dataProvider provideInvalidLists
     * @param array $white_list
     * @param array $black_list
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage are not valid
     */
    public function testInvalidListsFirewall($white_list, $black_list)
    {
        new Firewall($white_list, $black_list);
    }

    public function provideInvalidLists()
    {
        return [
            [['not a valid rule in whitelist'], ['127.0.0.1', '72.165.0.0/16', '::1']],
            [['72.165.0.0/1024'], ['127.0.0.1', '72.165.0.0/16', '::1']],
            [['127.0.0.1', '72.165.0.0/16', '::1'], ['not a valid rule in blacklist']],
            [['127.0.0.1', '72.165.0.0/16', '::1'], ['::1/1024']],
        ];
    }

    public function testConstructorArguments()
    {
        $white_list = ['72.165.0.0/16', '127.0.0.1'];
        $black_list = ['72.165.0.0/16', '::1'];

        $firewall = new Firewall($white_list, $black_list);

        $this->assertSame($white_list, $firewall->getWhiteList());
        $this->assertSame($black_list, $firewall->getBlackList());
    }

    public function testBlacklist()
    {
        $firewall = new Firewall([], ['72.165.0.0/16']);

        $this->assertTrue($firewall->shouldBlock(new IpAddress('72.165.1.2')));
        $this->assertTrue($firewall->shouldBlock(new IpAddress('72.165.1.3')));
        $this->assertFalse($firewall->shouldBlock(new IpAddress('127.0.0.1')));
    }
    
    public function testWhitelistTakesPrecedence()
    {
        $firewall = new Firewall(['72.165.1.2'], ['72.165.0.0/16']);

        $this->assertFalse($firewall->shouldBlock(new IpAddress('72.165.1.2')));
        $this->assertTrue($firewall->shouldBlock(new IpAddress('72.165.1.3')));
    }
}
