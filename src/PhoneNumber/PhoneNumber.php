<?php

/*
 * This file is part of the Active Collab project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\PhoneNumber;

class PhoneNumber implements PhoneNumberInterface
{
    public function __construct(
        private string $phoneNumber,
        private string $nationalPhoneNumber,
        private string $countryCode,
        private int $countryDialCode,
    )
    {
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getNationalPhoneNumber(): string
    {
        return $this->nationalPhoneNumber;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getCountryDialCode(): int
    {
        return $this->countryDialCode;
    }

    public function __toString(): string
    {
        return $this->phoneNumber;
    }
}
