<?php

/*
 * This file is part of the Active Collab project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\PhoneNumber\Country;

interface CountryInterface
{
    public function getCountryCode(): string;
    public function getName(): string;
    public function getDialCode(): int;
}
