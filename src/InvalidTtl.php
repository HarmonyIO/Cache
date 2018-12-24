<?php declare(strict_types=1);

namespace HarmonyIO\Cache;

class InvalidTtl extends Exception
{
    public function __construct(int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct('TTL can not be in the past.', $code, $previous);
    }
}
