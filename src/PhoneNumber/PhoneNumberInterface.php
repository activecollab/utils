<?php

/*
 * This file is part of the Active Collab project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\PhoneNumber;

use Stringable;

interface PhoneNumberInterface extends Stringable
{
    public function getPhoneNumber(): string;
    public function getNationalPhoneNumber(): string;
    public function getCountryCode(): string;
    public function getCountryDialCode(): int;
}
