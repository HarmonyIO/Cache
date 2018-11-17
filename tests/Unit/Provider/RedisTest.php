<?php declare(strict_types=1);

namespace HarmonyIO\CacheTest\Provider;

use Amp\Redis\Client;
use Amp\Success;
use HarmonyIO\Cache\Item;
use HarmonyIO\Cache\Key;
use HarmonyIO\Cache\Provider\Redis;
use HarmonyIO\PHPUnitExtension\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class RedisTest extends TestCase
{
    /** @var MockObject|Client */
    private $client;

    /** @var Key */
    private $key;

    public function setUp()
    {
        $this->client = $this->createMock(Client::class);
        $this->key    = $key = new Key('TheType', 'TheSource', 'TheHash');
    }

    public function testGet(): void
    {
        $this->client
            ->expects($this->once())
            ->method('get')
            ->willReturnCallback(function(string $key) {
                $this->assertSame('HarmonyIO_TheType_TheSource_TheHash', $key);

                return new Success(true);
            })
        ;

        (new Redis($this->client))->get($this->key);
    }

    public function testExistsWhenExist(): void
    {
        $this->client
            ->expects($this->once())
            ->method('exists')
            ->willReturn(new Success(true))
        ;

        $this->assertTrue((new Redis($this->client))->exists($this->key));
    }

    public function testExistsWhenNotExist(): void
    {
        $this->client
            ->expects($this->once())
            ->method('exists')
            ->willReturn(new Success(false))
        ;

        $this->assertFalse((new Redis($this->client))->exists($this->key));
    }

    public function testDelete(): void
    {
        $this->client
            ->expects($this->once())
            ->method('del')
            ->willReturnCallback(function(string $key) {
                $this->assertSame('HarmonyIO_TheType_TheSource_TheHash', $key);

                return new Success(true);
            })
        ;

        (new Redis($this->client))->delete($this->key);
    }

    public function testStore(): void
    {
        $item = new class($this->key) extends Item
        {
            public function __construct(Key $key)
            {
                parent::__construct($key, 'TheValue', 10);
            }
        };

        $this->client
            ->expects($this->once())
            ->method('set')
            ->willReturnCallback(function(string $key, string $value, int $ttl) {
                $this->assertSame('HarmonyIO_TheType_TheSource_TheHash', $key);
                $this->assertSame('TheValue', $value);
                $this->assertSame(10, $ttl);

                return new Success(true);
            })
        ;

        (new Redis($this->client))->store($item);
    }
}
