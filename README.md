# Utils

[![Build Status](https://travis-ci.org/activecollab/utils.svg?branch=master)](https://travis-ci.org/activecollab/utils)

This package is a playground for "little" PHP utilities that we use everyday. They are not complex or big enough to justify a separate package, but they are useful, and they may grow up to be full blown packages one day. That's the reason why they are all namespaced as directly under `ActiveCollab` namespace, not `ActiveCollab\Utils`.

## What's Inside

1. [Class Finder](#class-finder)
2. [Config Loader](#config-loader)
3. [Current Timestamp](#current-timestamp)
4. [Encryptor](#encryptor)
5. [Firewall](#firewall)
6. [Value Container](#value-container)
7. [URL](#url)

### Class Finder

### Config Loader

`ActiveCollab\ConfigLoader\ConfigLoaderInterface` - Interface specifies a way to load application configuration, while specifying requirements, like option presence, or non-empty value requirements.

#### Basic Usage

Example below demonstrates basic usage of config loader (we'll use `ArrayConfigLoader`, but you can use any other config loader implementation):

```php
<?php

namespace MyApp;

use ActiveCollab\ConfigLoader\ArrayConfigLoader;

$config_loader = (new ArrayConfigLoader('/path/to/file.php'))
    ->requirePresence('APPLICATION_VERSION')
    ->requireValue('LOG_HANDLER')
    ->requirePresenceWhen('LOG_HANDLER', 'grayloag', 'GRAYLOG_HOST', 'GRAYLOG_PORT')
    ->requireValueWhen('LOG_HANDLER', 'file', 'LOG_DIR_PATH')
    ->load();

if ($config_loader->hasValue('LOG_HANDLER')) {
    if ($config_loader->getValue('LOG_HANDLER') == 'file') {
        print 'Log dirs path is ' . $config_loader->getValue('LOG_DIR_PATH') . ".\n";
    } else {
        print 'Logs are sent to Graylog at ' . $config_loader->getValue('GRAYLOG_HOST') . ':' . $config_loader->getValue('GRAYLOG_PORT') . ".\n";    
    }
} else {
    print "Log handler not present.\n"; // Impossible case, because we value is required. Using this just to demonstrate `hasValue()` method.
}
```

As example above demonstrates, you can check for presence of option values by calling `hasValue()`. To get the value of an option, call `getValue()`.

#### Validators

There are four main validators that can be used to set config option requirements, and let config loader validate if all options that you need are present:

1. `requirePresence` - Require option presence. Value can be empty,
1. `requireValue` - Require option presence, and value must not be empty,
1. `requirePresenceWhen` - Conditional presence requirement; when option X has value Y require presence of option Z,
1. `requireValueWhen` - Conditional value requirement; when option X has value Y require presence of option Z, and its value must not be empty.

All four validators accept arrays of required fields:

```php
<?php

namespace MyApp;

use ActiveCollab\ConfigLoader\ArrayConfigLoader;

(new ArrayConfigLoader('/path/to/file.php'))
    ->requirePresence('X', 'Y', 'Z')
    ->requireValue('A', 'B', 'C')
    ->requirePresenceWhen('LOG_HANDLER', 'grayloag', 'X', 'Y', 'Z')
    ->requireValueWhen('LOG_HANDLER', 'file', 'A', 'B', 'C');
```

**Note**: Validators that add conditional requirements will make condition option required, and it's value will not be allowed to be empty.

#### Validation Errors

In case of a validation error (required option does not exist, or it is empty when value is required), and exception will be thrown:

```php
<?php

namespace MyApp;

use ActiveCollab\ConfigLoader\ArrayConfigLoader;
use ActiveCollab\ConfigLoader\Exception\ValidationException;

try {
    (new ArrayConfigLoader('/path/to/file.php'))
        ->requirePresence('APPLICATION_VERSION')
        ->requireValue('LOG_HANDLER')
        ->requirePresenceWhen('LOG_HANDLER', 'grayloag', 'GRAYLOG_HOST', 'GRAYLOG_PORT')
        ->requireValueWhen('LOG_HANDLER', 'file', 'LOG_DIR_PATH')
        ->load();
} catch (ValidationException $e) {
    print 'Config could not be loaded. Reason: ' . $e->getMessage() . "\n";
}
```

**Note**: Requirements can be set prior to calling `load()` method. If you try to set requirements after config option have been loaded, an exception will be thrown. 

### Current Timestamp

`ActiveCollab\CurrentTimestamp\CurrentTimestampInterface` - Interface specifies a way how to get a current timestamp. Default implementation uses a `time()` function call, but you can use any implementation, including timestamp locking (great for tests).
 
### Encryptor
 
`ActiveCollab\Encryptor\EncryptorInterface` - Interface specifies a way to encrypt and decrypt values. It does not specify how values are encrypted, or decrypted, just that they can be. All dependencies and implementation details need to go to the implementation, and specifics need to be configured via constructor arguments, or setters. Default implementation that uses AES 256 CBC is part of the package.

### Firewall

`ActiveCollab\Firewall\FirewallInterface` - This interface and implementation allow you to check IP addresses agains white and black address lists, and block unwanted traffic:

```php
<?php

namespace MyApp;

use ActiveCollab\Firewall\Firewall;
use ActiveCollab\Firewall\IpAddress;

$firewall = new Firewall(['72.165.1.2'], ['72.165.0.0/16']);

$firewall->shouldBlock(new IpAddress('72.165.1.2')); // No, address is white-listed.
$firewall->shouldBlock(new IpAddress('72.165.1.3')); // Yes, address is in the black-listed range.
```

### JSON

`ActiveCollab\Json\JsonEncoderInterface` - This interface and its implementation provide a way to encode JSON in a way that we usually do it, and to test if data is being encoded in tests.

```php
<?php

use ActiveCollab\Json\JsonEncoder;

print (new JsonEncoder())->encode(
    [
        'a' => 1,
        'b' => 2,
    ],
    true,
);
```

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

### URL

`ActiveCollab\Url\UrlInterface` - In the current form, implementation of this interface lets you easily add and remove query parameters to a known URL.

```php
<?php

use ActiveCollab\Url\Url;

print (new Url('https://activecollab.com'))->getExtendedUrl(
    [
        'utm_source' => 'activecollab',
        'utm_medium' => 'website',
        'utm_campaign' => 'footer',
    ],
);
```

## How to Add a Utility

1. Add auto-loading code under `autoload` -> `psr-4` in `composer.json`,
2. Update dependencies with `composer update`,
3. Implement and test your utility class,
4. Done.
