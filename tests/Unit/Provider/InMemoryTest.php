<?php declare(strict_types=1);

namespace HarmonyIO\CacheTest\Unit\Provider;

use HarmonyIO\Cache\Item;
use HarmonyIO\Cache\Key;
use HarmonyIO\Cache\Provider\InMemory;
use HarmonyIO\Cache\Ttl;
use HarmonyIO\PHPUnitExtension\TestCase;

class InMemoryTest extends TestCase
{
    /** @var Key */
    private $key;

    public function setUp(): void
    {
        $this->key = $key = new Key('TheType', 'TheSource', 'TheHash');
    }

    public function testGetReturnsNullWhenKeyDoesNotExist(): void
    {
        $this->assertNull((new InMemory())->get($this->key));
    }

    public function testGetReturnsItemWhenKeyExists(): void
    {
        $cache = new InMemory();

        $cache->store(new Item($this->key, 'TheValue', new Ttl(5)));

        $this->assertSame('TheValue', $cache->get($this->key));
    }

    public function testGetDoesNotContainExpiredItems(): void
    {
        $cache = new InMemory();

        $cache->store(new Item($this->key, 'TheValue', new Ttl(1)));

        sleep(2);

        $this->assertNull($cache->get($this->key));
    }

    public function testExistsReturnsFalseWhenKeyDoesNotExist(): void
    {
        $this->assertFalse((new InMemory())->exists($this->key));
    }

    public function testExistsReturnsTrueWhenKeyExists(): void
    {
        $cache = new InMemory();

        $cache->store(new Item($this->key, 'TheValue', new Ttl(5)));

        $this->assertTrue($cache->exists($this->key));
    }

    public function testExistsReturnsFalseForExpiredItems(): void
    {
        $cache = new InMemory();

        $cache->store(new Item($this->key, 'TheValue', new Ttl(1)));

        sleep(2);

        $this->assertFalse($cache->exists($this->key));
    }

    public function testDeletePurgesItemFromCache(): void
    {
        $cache = new InMemory();

        $cache->store(new Item($this->key, 'TheValue', new Ttl(5)));
        $cache->delete($this->key);

        $this->assertFalse($cache->exists($this->key));
    }

    public function testStoreStoresItem(): void
    {
        $cache = new InMemory();

        $cache->store(new Item($this->key, 'TheValue', new Ttl(5)));

        $this->assertTrue($cache->exists($this->key));
    }
}
