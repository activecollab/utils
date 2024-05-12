<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Utils\Test;

use ActiveCollab\CurrentTimestamp\CurrentTimestamp;
use ActiveCollab\Utils\Test\Base\TestCase;

class CurrentTimestampTest extends TestCase
{
    public function testDefaultImplementationUsesTime()
    {
        $this->assertSame(time(), (new CurrentTimestamp())->getCurrentTimestamp());
    }
}
