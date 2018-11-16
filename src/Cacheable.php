<?php declare(strict_types=1);

namespace HarmonyIO\Cache;

interface Cacheable
{
    public function getHash(): string;

    public function getValue(): string;
}
