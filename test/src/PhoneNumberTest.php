<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Utils\Test;

use ActiveCollab\PhoneNumber\Factory\PhoneNumberFactory;
use ActiveCollab\Utils\Test\Base\TestCase;
use InvalidArgumentException;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberTest extends TestCase
{
    /**
     * @dataProvider provideInvalidPhoneNumber
     */
    public function testWillRejectInvalidPhoneNumber(string $invalidPhoneNumber): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Phone number is not a valid E.164 number');

        (new PhoneNumberFactory(PhoneNumberUtil::getInstance()))->createPhoneNumber($invalidPhoneNumber);
    }

    public function provideInvalidPhoneNumber(): array
    {
        return [
            
            // Invalid phone number.
            ['1234567'],
            
            // Valid number, but not in E.164 format.
            ['1800 801 920'],
        ];
    }

    /**
     * @dataProvider provideValidPhoneNumbers
     */
    public function testWillProcessValidPhoneNumber(
        string $validPhoneNumber,
        string $expectedPhoneNumber,
        string $expectedNationalPhoneNumber,
        string $expectedCountryCode,
        int $expectedCountryDialCode,
    ): void
    {
        $phoneNumber = (new PhoneNumberFactory(PhoneNumberUtil::getInstance()))->createPhoneNumber($validPhoneNumber);

        $this->assertSame($expectedPhoneNumber, $phoneNumber->getPhoneNumber());
        $this->assertSame($expectedNationalPhoneNumber, $phoneNumber->getNationalPhoneNumber());
        $this->assertSame($expectedCountryCode, $phoneNumber->getCountryCode());
        $this->assertSame($expectedCountryDialCode, $phoneNumber->getCountryDialCode());
    }

    public function provideValidPhoneNumbers(): array
    {
        return [

            // 1800 801 920
            [
                '+61 1800 801 920',
                '+611800801920',
                '1800801920',
                'AU',
                61,
            ],

            // +44 20 8759 9036
            [
                '+44 20 8759 9036',
                '+442087599036',
                '2087599036',
                'GB',
                44,
            ],

            // +1 800 444 4444
            [
                '+1 800 444 4444',
                '+18004444444',
                '8004444444',
                'US',
                1,
            ],

            // +1 213 621 0002
            [
                '+1 213 621 0002',
                '+12136210002',
                '2136210002',
                'US',
                1,
            ],

        ];
    }
}