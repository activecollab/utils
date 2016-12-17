<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\Utils\Test;

use ActiveCollab\Utils\Test\Base\TestCase;
use ActiveCollab\ValueContainer\Request\RequestValueContainer;
use Zend\Diactoros\Request;
use Zend\Diactoros\ServerRequest;

class RequestValueContainerTest extends TestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Request not set.
     */
    public function testHasValueWithNoRequest()
    {
        (new RequestValueContainer('test_key'))->hasValue();
    }

    public function testHasValue()
    {
        $request = new ServerRequest();

        $container = new RequestValueContainer('test_key');
        $container->setRequest($request);

        $this->assertFalse($container->hasValue());

        $request = $request->withAttribute('test_key', 123);
        $container->setRequest($request);

        $this->assertTrue($container->hasValue());
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Request not set.
     */
    public function testGetValueWithNoRequest()
    {
        (new RequestValueContainer('test_key'))->getValue();
    }

    public function testGetValue()
    {
        $request = new ServerRequest();

        $container = new RequestValueContainer('test_key');
        $container->setRequest($request);

        $this->assertNull($container->getValue());

        $request = $request->withAttribute('test_key', 123);
        $container->setRequest($request);

        $this->assertSame(123, $container->getValue());
    }
}
