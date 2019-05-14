<?php declare(strict_types=1);

namespace HarmonyIO\CacheTest\Unit;

use HarmonyIO\Cache\InvalidTtl;
use HarmonyIO\Cache\Ttl;
use HarmonyIO\PHPUnitExtension\TestCase;

class TtlTest extends TestCase
{
    public function testConstructorThrowsWhenTtlIsNegative(): void
    {
        $this->expectException(InvalidTtl::class);
        $this->expectExceptionMessage('TTL can not be in the past.');

        new Ttl(-1);
    }

    public function testConstructorThrowsWhenTtlIsMoreNegative(): void
    {
        $this->expectException(InvalidTtl::class);
        $this->expectExceptionMessage('TTL can not be in the past.');

        new Ttl(-3);
    }

    public function testConstructorThrowsWhenExpirationDateTimeIsInThePast(): void
    {
        $this->expectException(InvalidTtl::class);
        $this->expectExceptionMessage('TTL can not be in the past.');

        $expirationTime = (new \DateTimeImmutable())->sub(new \DateInterval('PT3M'));

        Ttl::fromDateTime($expirationTime);
    }

    public function testGetTtlInSeconds(): void
    {
        $this->assertSame(10, (new Ttl(10))->getTtlInSeconds());
    }
}
