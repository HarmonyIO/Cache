# Cache

[![Latest Stable Version](https://poser.pugx.org/harmonyio/cache/v/stable)](https://packagist.org/packages/harmonyio/cache)
[![Build Status](https://travis-ci.org/HarmonyIO/Cache.svg?branch=master)](https://travis-ci.org/HarmonyIO/Cache)
[![Build status](https://ci.appveyor.com/api/projects/status/3ohrrm40gmmemd7i/branch/master?svg=true)](https://ci.appveyor.com/project/PeeHaa/cache/branch/master)
[![Coverage Status](https://coveralls.io/repos/github/HarmonyIO/Cache/badge.svg?branch=master)](https://coveralls.io/github/HarmonyIO/Cache?branch=master)
[![License](https://poser.pugx.org/harmonyio/cache/license)](https://packagist.org/packages/harmonyio/cache)

Async caching library

## Requirements

- PHP 7.3+
- Redis (if wanting to use the Redis caching provider)

In addition for non-blocking context one of the following event libraries should be installed:

- [ev](https://pecl.php.net/package/ev)
- [event](https://pecl.php.net/package/event)
- [php-uv](https://github.com/bwoebi/php-uv)

## Installation

```
composer require harmonyio/cache
```

## Usage

This library both provides interfaces for working with the cache from external libraries as well as providing a way to interface with the cache directly.

This is mostly intended to be used by cache aware libraries and should in most cases not be worked with directly as this is a low-level library.

### Complete example usage

```php
<?php declare(strict_types=1);

namespace Foo;

use Amp\Dns;
use Amp\Dns\Record;
use Amp\Redis\Client;
use HarmonyIO\Cache\Item;
use HarmonyIO\Cache\Key;
use HarmonyIO\Cache\Provider\Redis;
use HarmonyIO\Cache\Ttl;
use function Amp\Dns\query;
use function Amp\wait;

require_once __DIR__ . '/path/to-autoload.php';

// create the cache storage connection
$cache = new Redis(new Client('tcp://127.0.0.1:6379'));

// create the key for the item to be stored
$key = new Key('DnsRequest', 'www.example.org', md5('www.example.org' . json_encode(['type' => 'A'])));

// get the data you want to cache (in this cache a DNS lookup)
$result = wait(query('www.example.org', Record::A));

// create the cacheable item to be stored in the cache for 1 hour
$itemToCache = new Item($key, json_encode($result), new Ttl(Ttl::ONE_HOUR));

// store the cached item
$cache->store($itemToCache);

// retrieve the item
var_dump($cache->get($key));
```

### Working with the cache

#### Cache interface

The cache provides 4 methods to interface with:

```php
<?php declare(strict_types=1);

namespace HarmonyIO\Cache;

use Amp\Promise;

interface Cache
{
    /**
     * @return Promise<null|string>
     */
    public function get(Key $key): Promise;

    /**
     * @return Promise<bool>
     */
    public function exists(Key $key): Promise;

    /**
     * @return Promise<null>
     */
    public function delete(Key $key): Promise;

    /**
     * @return Promise<bool>
     */
    public function store(Item $item): Promise;
}
```

#### Cacheable items

To build an item to be stored in the cache create an instance of `\HarmonyIO\Cache\Item.php`:

```php
<?php declare(strict_types=1);

namespace Foo;

use HarmonyIO\Cache\Item;
use HarmonyIO\Cache\Key;
use HarmonyIO\Cache\Ttl;

$key = new Key('HttpRequest', 'https://httpbin.org/get', md5('https://httpbin.org/get'));

$itemToCache = new Item($key, 'Result from the http request', new Ttl(60));
```

The above example will create an item to be cached (in this case a fake response from an http call) and stores it in the cache for 60 seconds.

##### TTL

There are three main ways to define the TTL for items to be cached.

By defining the expiration time:

```php
<?php declare(strict_types=1);

namespace Foo;

use HarmonyIO\Cache\Ttl;

$ttl = Ttl::fromDateTime((new \DateTimeImmutable())->add(new \DateInterval('P1D')));
```

*`Ttl::fromDateTime` expects an instance of \DateTimeInterface*

By defining the TTL in seconds:

```php
<?php declare(strict_types=1);

namespace Foo;

use HarmonyIO\Cache\Ttl;

$ttl = new Ttl(10);
```

By defining the TTL in seconds using one of the provided common used TTL values as constants:

```php
<?php declare(strict_types=1);

namespace Foo;

use HarmonyIO\Cache\Ttl;

$ttl = new Ttl(Ttl::ONE_HOUR);
```

##### Key

The key cacheable items use consists of three parts:

- The type
- The source
- The hash

###### Type

The type refers to the type of the resource that is being stored. Common use cases are for example: `HttpRequest` or `DatabaseQuery`.

The type is used as a way of grouping cached items in the cache and to provide a way to easily purge antire groups of items from the cache.

###### Source

The type refers to the identifier of the resource itself. For http requests this would commonly be the URL. Or for database queries this would be the query.

The source is used as a way of grouping cached items in the cache and to provide a way to easily purge entire groups of items from the cache.

###### Hash

The hash must be a unique identifier for one specific cacheable item in the cache. For example for a database query an hash created from both the prepared statement as well as the bound parameters:

The following pseudo-code illustrates what a valid hash could be:

```php
$hash = md5('SELECT * FROM users WHERE name = ?' . json_encode(['Bobby Tables']));
```

#### Providers

Currently two caching providers are implemented as part of this library: `Redis` and `InMemory`.

##### Redis provider

The recommended provider to use is the redis cache provider: `\HarmonyIO\Cache\Provider\Redis`. To set up the Redis provider inject the Redis client with the correct Redis address:

```php
<?php declare(strict_types=1);

namespace Foo;

use Amp\Redis\Client;
use HarmonyIO\Cache\Provider\Redis;

$cache = new Redis(new Client('tcp://127.0.0.1:6379'));
```

##### InMemory (array) provider

This library also provides an in-memory (simple php array) provider: `\HarmonyIO\Cache\Provider\InMemory`. This is more meant for development purposes and should probably not be used in production.  
To set up the InMemory:

```php
<?php declare(strict_types=1);

namespace Foo;

use HarmonyIO\Cache\Provider\InMemory;

$cache = new InMemory();
```

### `\HarmonyIO\Cache\CacheableRequest` and `\HarmonyIO\Cache\CacheableResponse` interfaces

This library provides two interfaces to be used in external cache aware libraries:

- `\HarmonyIO\Cache\CacheableRequest`
- `\HarmonyIO\Cache\CacheableResponse`

#### `\HarmonyIO\Cache\CacheableRequest`

Any cacheable actions / resources that can be done in a cacheable aware library should accept an instance of `\HarmonyIO\Cache\CacheableRequest`:

```php
<?php declare(strict_types=1);

namespace HarmonyIO\Cache;

interface CacheableRequest
{
    public function getCachingKey(): Key;

    public function getTtl(): Ttl;
}
```

This interface enforces cacheable requests to have both a key and a TTL.

An example of a cacheable request can be found at the HttpClient package: https://github.com/HarmonyIO/Http-Client/blob/56db13437e388b178c2d0b926f16a906aa48f9ae/src/Message/CachingRequest.php

*Both the request as well as the eventual response will result in an cacheable item which can be stored in the cache.*

#### `\HarmonyIO\Cache\CacheableResponse`

Any cacheable actions / resources that can be done in a cacheable aware library should result in an instance of `\HarmonyIO\Cache\CacheableResponse`:

```php
<?php declare(strict_types=1);

namespace HarmonyIO\Cache;

interface CacheableResponse extends \Serializable
{
}
```

This makes sure responses from the cacheable actions / resources can be safely stored in cache.

*Both the request as well as the eventual response will result in an cacheable item which can be stored in the cache.*
