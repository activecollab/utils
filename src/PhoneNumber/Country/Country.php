<?php

/*
 * This file is part of the Active Collab project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\PhoneNumber\Country;

class Country implements CountryInterface
{
    public function __construct(
        private string $countryCode,
        private string $name,
        private int $dialCode,
    )
    {
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDialCode(): int
    {
        return $this->dialCode;
    }
}
