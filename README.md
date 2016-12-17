# Utils

[![Build Status](https://travis-ci.org/activecollab/utils.svg?branch=master)](https://travis-ci.org/activecollab/utils)

This package is a playground for "little" PHP utilities that we use everyday. They are not complex or big enough to justify a separate package, but they are useful, and they may grow up to be full blown packages one day. That's the reason why they are all namespaced as directly under `ActiveCollab` namespace, not `ActiveCollab\Utils`.

## What's Inside

### Current Timestamp

`ActiveCollab\CurrentTimestamp\CurrentTimestampInterface` - Interface specifies a way how to get a current timestamp. Default implementation uses a `time()` function call, but you can use any implementation, including timestamp locking (great for tests).
 
### Encryptor
 
`ActiveCollab\Encryptor\EncryptorInterface` - Interface specifies a way to encrypt and decrypt values. It does not specify how values are encrypted, or decrypted, just that they can be. All dependencies and implementation details need to go to the implementation, and specifics need to be configured via constructor arguments, or setters. Default implementation that uses AES 256 CBC is part of the package.

### Value Container

`ActiveCollab\ValueContainer\ValueContainerInterface` - Interface that enables value access abstraction. For example, it can be used to abstract access to a value in a way that class does not know where and how value is store, it just knows that it can check for its presence, get the value, set it or remove it.

Package includes `ActiveCollab\ValueContainer\Request\RequestValueContainerInterface`. This interface is used when you have PSR-7 request that contains the value as an attribute. Default implementation, that is included with the package, can read and write to the request:
 
```php
<?php

use ActiveCollab\ValueContainer\Request\RequestValueContainer;
use Psr\Http\Message\ServerRequestInterface;

/** @var ServerRequestInterface $request */
$request = $request->withAttribute('value_that_we_need', [1, 2, 3]);

$value_container = (new RequestValueContainer('value_that_we_need'))
    ->setRequest($request);

print_r($value_container->getValue()); // Prints array.
```

## How to Add a Utility

1. Add autoloading code under `autoload` -> `psr-4` in `composer.json`,
1. Update dependencies with `composer update`,
1. Implement and test your utility class,
1. Done.
