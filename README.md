# Utils

[![Build Status](https://travis-ci.org/activecollab/utils.svg?branch=master)](https://travis-ci.org/activecollab/utils)

This package is a playground for "little" PHP utilities that we use everyday. They are not complex or big enough to justify a separate package, but they are useful, and they may grow up to be full blown packages one day. That's the reason why they are all namespaced as directly under `ActiveCollab` namespace, not `ActiveCollab\Utils`.

## What's Inside

1. `ActiveCollab\CurrentTimestamp\CurrentTimestampInterface` - Interface specifies a way how to get a current timestamp. Default implementation uses a `time()` function call, but you can use any implementation, including timestamp locking (great for tests). 

## How to Add a Utility

1. Add autoloading code under `autoload` -> `psr-4` in `composer.json`,
1. Update dependencies with `composer update`,
1. Implement and test your utility class,
1. Done.
