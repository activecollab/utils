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
}
