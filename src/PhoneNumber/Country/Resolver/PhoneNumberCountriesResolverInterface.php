<?php

/*
 * This file is part of the Active Collab project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\PhoneNumber\Country\Resolver;

interface PhoneNumberCountriesResolverInterface
{
    public function getCountries(): array;
}
