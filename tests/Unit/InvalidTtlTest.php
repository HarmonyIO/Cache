<?php declare(strict_types=1);

namespace HarmonyIO\CacheTest\Unit;

use HarmonyIO\Cache\InvalidTtl;
use HarmonyIO\PHPUnitExtension\TestCase;

class InvalidTtlTest extends TestCase
{
    public function testConstructorSetsCorrectMessage(): void
    {
        $this->expectException(InvalidTtl::class);
        $this->expectExceptionMessage('TTL can not be in the past.');

        throw new InvalidTtl();
    }

    public function testConstructorSetsCorrectDefaultCode(): void
    {
        $this->expectException(InvalidTtl::class);
        $this->expectExceptionCode(0);

        throw new InvalidTtl();
    }
}
