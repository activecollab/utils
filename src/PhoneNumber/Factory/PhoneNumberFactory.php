<?php

/*
 * This file is part of the Active Collab project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\PhoneNumber\Factory;

use InvalidArgumentException;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use ActiveCollab\PhoneNumber\PhoneNumber;
use ActiveCollab\PhoneNumber\PhoneNumberInterface;

class PhoneNumberFactory implements PhoneNumberFactoryInterface
{
    public function __construct(
        private PhoneNumberUtil $phoneNumberUtil,
    )
    {
    }

    public function createPhoneNumber(string $phoneNumber): PhoneNumberInterface
    {
        if (!str_starts_with($phoneNumber, '+')) {
            throw new InvalidArgumentException('Phone number is not a valid E.164 number.');
        }

        try {
            $parsedNumber = $this->phoneNumberUtil->parse($phoneNumber);
        } catch (NumberParseException $e) {
            throw new InvalidArgumentException(
                'Phone number is not valid.',
                previous: $e,
            );
        }

        if (!$this->phoneNumberUtil->isValidNumber($parsedNumber)) {
            throw new InvalidArgumentException('Phone number is not valid.');
        }

        return new PhoneNumber(
            $this->phoneNumberUtil->format($parsedNumber, PhoneNumberFormat::E164),
            $parsedNumber->getNationalNumber(),
            $this->phoneNumberUtil->getRegionCodeForNumber($parsedNumber),
            $parsedNumber->getCountryCode(),
        );
    }

    public function getCountryCodeForRegion(string $phoneNumberCountry): int
    {
        try {
            return $this->phoneNumberUtil->getCountryCodeForRegion($phoneNumberCountry);
        } catch (InvalidArgumentException) {
            return 0;
        }
    }
}
