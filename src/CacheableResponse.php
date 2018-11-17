<?php declare(strict_types=1);

namespace HarmonyIO\Cache;

interface CacheableResponse
{
    public static function initializeFromCache(string $cachedData);
}
