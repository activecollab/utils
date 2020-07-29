<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\Utils\Test;

use ActiveCollab\Encryptor\Encryptor;
use ActiveCollab\Utils\Test\Base\TestCase;
use InvalidArgumentException;

class EncryptionTest extends TestCase
{
    public function testExceptionOnEmptyKey()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Key needs to be a non-empty string value");

        new Encryptor('');
    }

    public function testAcceptableKeys()
    {
        $this->expectNotToPerformAssertions();

        new Encryptor('not 256 bit');
        new Encryptor('770A8A65DA156D24EE2A093277530142');
    }

    public function testEncryptAndDecryptValues()
    {
        $encryptor = new Encryptor('770A8A65DA156D24EE2A093277530142');

        $value_to_encrypt = 'Super secret value';

        $encrypted_value = $encryptor->encrypt($value_to_encrypt);

        $this->assertIsString('string', $encrypted_value);
        $this->assertNotEmpty($encrypted_value);
        $this->assertNotEquals($value_to_encrypt, $encrypted_value);

        $this->assertEquals('Super secret value', $encryptor->decrypt($encrypted_value));
    }

    public function testEncryptEmptyValue()
    {
        $encryptor = new Encryptor('770A8A65DA156D24EE2A093277530142');

        $value_to_encrypt = '';

        $encrypted_value = $encryptor->encrypt($value_to_encrypt);

        $this->assertIsString('string', $encrypted_value);
        $this->assertNotEmpty($encrypted_value);
        $this->assertNotEquals($value_to_encrypt, $encrypted_value);

        $this->assertEquals($value_to_encrypt, $encryptor->decrypt($encrypted_value));
    }

    public function testEncryptCastsToString()
    {
        $encryptor = new Encryptor('770A8A65DA156D24EE2A093277530142');

        $value_to_encrypt = 123;

        $encrypted_value = $encryptor->encrypt($value_to_encrypt);

        $this->assertIsString('string', $encrypted_value);
        $this->assertNotEmpty($encrypted_value);
        $this->assertNotEquals($value_to_encrypt, $encrypted_value);

        $this->assertNotSame($value_to_encrypt, $encryptor->decrypt($encrypted_value));
        $this->assertSame((string) $value_to_encrypt, $encryptor->decrypt($encrypted_value));
    }

    public function testDecryptEmptyValue()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Value is required.");

        (new Encryptor('770A8A65DA156D24EE2A093277530142'))->decrypt('');
    }

    public function testDecryptInvalidValue()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Separator not found in the encrypted data.");

        (new Encryptor('770A8A65DA156D24EE2A093277530142'))->decrypt('not encrypted');
    }
}
