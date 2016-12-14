<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\Utils\Test;

use ActiveCollab\Utils\Test\Base\TestCase;
use ActiveCollab\ValueContainer\ValueContainer;

/**
 * @package ActiveCollab\Utils\Test
 */
class ValueContainerTest extends TestCase
{
    public function testValueIsNotSetByDefault()
    {
        $this->assertFalse((new ValueContainer())->hasValue());
    }

    public function testSetValue()
    {
        $container = new ValueContainer();

        $this->assertNull($container->getValue());
        $container->setValue([1, 2, 3]);
        $this->assertSame([1, 2, 3], $container->getValue());
    }

    public function testRemoveValue()
    {
        $container = new ValueContainer();

        $container->setValue([1, 2, 3]);
        $this->assertSame([1, 2, 3], $container->getValue());

        $container->removeValue();

        $this->assertFalse($container->hasValue());
        $this->assertNull($container->getValue());
    }
}
