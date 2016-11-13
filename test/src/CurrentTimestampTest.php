<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\Utils\Test;

use ActiveCollab\Utils\CurrentTimestamp\CurrentTimestamp;
use ActiveCollab\Utils\Test\Base\TestCase;

/**
 * @package ActiveCollab\Utils\Test
 */
class CurrentTimestampTest extends TestCase
{
    public function testDefaultImplementationUsesTime()
    {
        $this->assertSame(time(), (new CurrentTimestamp())->getCurrentTimestamp());
    }
}
