<?php

/*
 * This file is part of the Active Collab Cookies project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Utils\Test\Cookies;

use ActiveCollab\Cookies\Cookies;
use ActiveCollab\Cookies\CookiesInterface;
use ActiveCollab\Utils\Test\Base\CookiesTestCase;
use Dflydev\FigCookies\Cookie as FigCookie;
use Dflydev\FigCookies\Cookies as FigCookies;

class PrefixTest extends CookiesTestCase
{
    private CookiesInterface $cookies;

    public function setUp(): void
    {
        parent::setUp();

        $this->setCookies(
            [
                'other_websites_cookie' => 123,
                'prefix_test_our_cookie' => 987,
            ]
        );

        $this->cookies = (new Cookies())->prefix('prefix_test_');
    }

    /**
     * Test if exists checks for prefixed values.
     */
    public function testExists()
    {
        $this->assertFalse($this->cookies->exists($this->request, 'other_websites_cookie'));
        $this->assertTrue($this->cookies->exists($this->request, 'our_cookie'));
    }

    /**
     * Test if get returns prefixed values.
     */
    public function testGet()
    {
        $this->assertEmpty($this->cookies->get($this->request, 'other_websites_cookie'));
        $this->assertSame('987', $this->cookies->get($this->request, 'our_cookie'));
    }

    /**
     * Test if set adds prefixed cookie value.
     */
    public function testSet()
    {
        $this->assertFalse($this->cookies->exists($this->request, 'new_cookie'));
        [
            $this->request,
            $this->response,
        ] = $this->cookies->set($this->request, $this->response, 'new_cookie', 'new_cookie_value');

        $this->assertTrue($this->cookies->exists($this->request, 'new_cookie'));
        $this->assertSame('new_cookie_value', $this->cookies->get($this->request, 'new_cookie'));
    }

    /**
     * Test if remove unsets prefixed cookie value.
     */
    public function testRemove()
    {
        $this->assertTrue($this->cookies->exists($this->request, 'our_cookie'));
        [
            $this->request,
            $this->response,
        ] = $this->cookies->remove($this->request, $this->response, 'our_cookie');

        $this->assertFalse($this->cookies->exists($this->request, 'our_cookie'));
    }

    /**
     * Add cookies to the request.
     *
     * @param array $cookies
     */
    private function setCookies(array $cookies)
    {
        $cookie_jar = FigCookies::fromRequest($this->request);

        foreach ($cookies as $k => $v) {
            $cookie_jar = $cookie_jar->with(new FigCookie($k, $v));
        }

        $this->request = $cookie_jar->renderIntoCookieHeader($this->request);
    }
}
