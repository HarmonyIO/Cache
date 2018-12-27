<?php declare(strict_types=1);

namespace HarmonyIO\CacheTest\Integration\Provider;

use Amp\Redis\Client;
use HarmonyIO\Cache\Item;
use HarmonyIO\Cache\Key;
use HarmonyIO\Cache\Provider\Redis;
use HarmonyIO\Cache\Ttl;
use HarmonyIO\PHPUnitExtension\TestCase;

class RedisTest extends TestCase
{
    /** @var Redis */
    private $cache;

    public function setUp(): void
    {
        $this->cache = new Redis(new Client(REDIS_ADDRESS));
    }

    public function testThatItemGetsStored(): void
    {
        $key = new Key('TheType', 'TheSource', 'TheHash1');

        $this->assertNull($this->cache->get($key));

        $this->cache->store(new Item($key, 'TheValue', new Ttl(5)));

        $this->assertSame('TheValue', $this->cache->get($key));
    }

    public function testThatItemGetsExists(): void
    {
        $key = new Key('TheType', 'TheSource', 'TheHash2');

        $this->assertFalse($this->cache->exists($key));

        $this->cache->store(new Item($key, 'TheValue', new Ttl(5)));

        $this->assertTrue($this->cache->exists($key));
    }

    public function testThatItemGetsDeleted(): void
    {
        $key = new Key('TheType', 'TheSource', 'TheHash3');

        $this->cache->store(new Item($key, 'TheValue', new Ttl(5)));

        $this->assertTrue($this->cache->exists($key));

        $this->cache->delete($key);

        $this->assertFalse($this->cache->exists($key));
    }

    public function testThatItemExpires(): void
    {
        $key = new Key('TheType', 'TheSource', 'TheHash3');

        $this->cache->store(new Item($key, 'TheValue', new Ttl(1)));

        $this->assertTrue($this->cache->exists($key));

        sleep(2);

        $this->assertFalse($this->cache->exists($key));
    }
}
