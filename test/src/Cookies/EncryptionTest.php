<?php

/*
 * This file is part of the Active Collab Cookies project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Utils\Test\Cookies;

use ActiveCollab\Cookies\Cookies;
use ActiveCollab\Encryptor\Encryptor;
use ActiveCollab\Utils\Test\Base\CookiesTestCase;
use Dflydev\FigCookies\Cookies as FigCookies;
use InvalidArgumentException;

class EncryptionTest extends CookiesTestCase
{
    public function testCookieValueEncryption()
    {
        $cookies = (new Cookies())->encryptor(new Encryptor('770A8A65DA156D24EE2A093277530142'));

        [
            $this->request,
            $this->response,
        ] = $cookies->set(
            $this->request,
            $this->response,
            'encrypted_var',
            'value to encrypt'
        );

        $raw_value = FigCookies::fromRequest($this->request)->get('encrypted_var')->getValue();

        $this->assertNotEmpty($raw_value);
        $this->assertNotEquals('value to encrypt', $raw_value);

        $set_cookie_header = $this->response->getHeaderLine('Set-Cookie');
        $this->assertNotEmpty($set_cookie_header);
        $this->assertStringContainsString('encrypted_var', $set_cookie_header);
        $this->assertStringNotContainsString('value to encrypt', $set_cookie_header);

        $this->assertEquals('value to encrypt', $cookies->get($this->request, 'encrypted_var'));
    }

    public function testCookieCanBeSetRawUsingSettings()
    {
        $cookies = (new Cookies())->encryptor(new Encryptor('770A8A65DA156D24EE2A093277530142'));

        [
            $this->request,
            $this->response,
        ] = $cookies->set(
            $this->request,
            $this->response,
            'non_encrypted_var',
            'this value should be as is',
            [
                'encrypt' => false,
            ]
        );

        $set_cookie_header = $this->response->getHeaderLine('Set-Cookie');
        $this->assertNotEmpty($set_cookie_header);
        $this->assertStringContainsString('non_encrypted_var', $set_cookie_header);
        $this->assertStringContainsString(urlencode('this value should be as is'), $set_cookie_header);

        $this->assertSame(
            'this value should be as is',
            $cookies->get(
                $this->request,
                'non_encrypted_var',
                null,
                false
            )
        );
    }

    public function testExceptionOnUnencryptedValueRanThroughEncryptor()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Separator not found in the encrypted data');

        $cookies = (new Cookies())->encryptor(new Encryptor('770A8A65DA156D24EE2A093277530142'));

        [$this->request, $this->response] = $cookies->set(
            $this->request,
            $this->response,
            'non_encrypted_var',
            'this value should be as is',
            [
                'encrypt' => false,
            ]
        );

        $cookies->get($this->request, 'non_encrypted_var');
    }
}
