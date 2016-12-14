<?php

/*
 * This file is part of the Active Collab Utils project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

namespace ActiveCollab\Utils\Test;

use ActiveCollab\Encryptor\Encryptor;
use ActiveCollab\Utils\Test\Base\TestCase;

/**
 * @package ActiveCollab\Cookies\Test
 */
class EncryptionTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Key needs to be a non-empty string value
     */
    public function testExceptionOnEmptyKey()
    {
        new Encryptor('');
    }

    public function testAcceptableKeys()
    {
        new Encryptor('not 256 bit');
        new Encryptor('770A8A65DA156D24EE2A093277530142');
    }

    public function testEncryptAndDecryptValues()
    {
        $encryptor = new Encryptor('770A8A65DA156D24EE2A093277530142');

        $value_to_encrypt = 'Super secret value';

        $encrypted_value = $encryptor->encrypt($value_to_encrypt);

        $this->assertInternalType('string', $encrypted_value);
        $this->assertNotEmpty($encrypted_value);
        $this->assertNotEquals($value_to_encrypt, $encrypted_value);

        $this->assertEquals('Super secret value', $encryptor->decrypt($encrypted_value));
    }

    public function testEncryptEmptyValue()
    {
        $encryptor = new Encryptor('770A8A65DA156D24EE2A093277530142');

        $value_to_encrypt = '';

        $encrypted_value = $encryptor->encrypt($value_to_encrypt);

        $this->assertInternalType('string', $encrypted_value);
        $this->assertNotEmpty($encrypted_value);
        $this->assertNotEquals($value_to_encrypt, $encrypted_value);

        $this->assertEquals($value_to_encrypt, $encryptor->decrypt($encrypted_value));
    }

    public function testEncryptCastsToString()
    {
        $encryptor = new Encryptor('770A8A65DA156D24EE2A093277530142');

        $value_to_encrypt = 123;

        $encrypted_value = $encryptor->encrypt($value_to_encrypt);

        $this->assertInternalType('string', $encrypted_value);
        $this->assertNotEmpty($encrypted_value);
        $this->assertNotEquals($value_to_encrypt, $encrypted_value);

        $this->assertNotSame($value_to_encrypt, $encryptor->decrypt($encrypted_value));
        $this->assertSame((string) $value_to_encrypt, $encryptor->decrypt($encrypted_value));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Value is required.
     */
    public function testDecryptEmptyValue()
    {
        (new Encryptor('770A8A65DA156D24EE2A093277530142'))->decrypt('');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Separator not found in the encrypted data.
     */
    public function testDecryptInvalidValue()
    {
        (new Encryptor('770A8A65DA156D24EE2A093277530142'))->decrypt('not encrypted');
    }
}
