<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Utils\Test;

use ActiveCollab\Json\JsonEncoder;
use ActiveCollab\Utils\Test\Base\TestCase;

class JsonEncoderTest extends TestCase
{
    public function testWillEncodeJson(): void
    {
        $this->assertSame(
            '{"one":"two"}',
            (new JsonEncoder())->encode(['one' => 'two']),
        );
    }

    public function testWillEncodePrettyJson(): void
    {
        $this->assertStringContainsString(
            "\n",
            (new JsonEncoder())->encode(['one' => 'two'], true),
        );
    }
}