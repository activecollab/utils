<?php

/*
 * This file is part of the Active Collab project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Utils\Test\Firewall;

use ActiveCollab\Utils\Test\Base\TestCase;
use ActiveCollab\Firewall\IpAddress;
use InvalidArgumentException;

class IpAddressTest extends TestCase
{
    /**
     * @dataProvider provideValidAddresses
     */
    public function testValueAddress(string $address_to_test): void
    {
        $this->assertSame($address_to_test, (new IpAddress($address_to_test))->getIpAddress());
    }

    public function provideValidAddresses(): array
    {
        return [
            ['127.0.0.1'],
            ['98.139.180.149'],
            ['::1'],
            ['2002:4559:1FE2::4559:1FE2'],
        ];
    }

    /**
     * @dataProvider provideInvalidAddresses
     */
    public function testInvalidAddress(string $address_to_test): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("not a valid IP address");

        new IpAddress($address_to_test);
    }

    public function provideInvalidAddresses(): array
    {
        return [
            ['127.0.0'],
            [':1'],
            ['not an address'],
            ['123'],
        ];
    }

    public function testOnList()
    {
        $list = [
            '127.0.0.1',
            '72.165.0.0/16',
        ];

        $this->assertTrue((new IpAddress('127.0.0.1'))->isOnList($list));
        $this->assertTrue((new IpAddress('72.165.0.0'))->isOnList($list));
        $this->assertTrue((new IpAddress('72.165.255.255'))->isOnList($list));
        $this->assertFalse((new IpAddress('98.139.180.149'))->isOnList($list));
    }
}
