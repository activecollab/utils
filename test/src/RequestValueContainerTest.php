<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Utils\Test;

use ActiveCollab\Utils\Test\Base\TestCase;
use ActiveCollab\ValueContainer\Request\RequestValueContainer;
use LogicException;
use Zend\Diactoros\Request;
use Zend\Diactoros\ServerRequest;

class RequestValueContainerTest extends TestCase
{
    public function testHasValueWithNoRequest()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Request not set.");

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

    public function testGetValueWithNoRequest()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Request not set.");

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
