<?php

/*
 * This file is part of the Active Collab project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\PhoneNumber\Country\Resolver;

use Giggsey\Locale\Locale;
use libphonenumber\PhoneNumberUtil;
use ActiveCollab\PhoneNumber\Country\Country;

class PhoneNumberCountriesResolver implements PhoneNumberCountriesResolverInterface
{
    public function __construct(
        private PhoneNumberUtil $numberUtil,
    )
    {
    }

    public function getCountries(): array
    {
        $result = [];

        foreach ($this->numberUtil->getSupportedRegions() as $region) {
            $result[] = new Country(
                $region,
                Locale::getDisplayRegion('-' . $region, 'en'),
                $this->numberUtil->getCountryCodeForRegion($region),
            );
        }

        return $result;
    }
}
