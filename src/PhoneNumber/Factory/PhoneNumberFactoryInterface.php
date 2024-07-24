<?php

/*
 * This file is part of the Active Collab project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\PhoneNumber\Factory;

use ActiveCollab\PhoneNumber\PhoneNumberInterface;

interface PhoneNumberFactoryInterface
{
    public function createPhoneNumber(string $phoneNumber): PhoneNumberInterface;
    public function getCountryCodeForRegion(string $phoneNumberCountry): int;
}
