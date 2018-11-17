<?php declare(strict_types=1);

namespace HarmonyIO\Cache;

interface CacheableRequest
{
    public function getCachingKey(): Key;

    public function getTtl(): int;
}
