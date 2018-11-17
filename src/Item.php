<?php declare(strict_types=1);

namespace HarmonyIO\Cache;

class Item
{
    /** @var Key */
    private $key;

    /** @var string */
    private $value;

    /** @var int */
    private $ttlInSeconds;

    public function __construct(Key $key, string $value, int $ttlInSeconds = 0)
    {
        $this->key          = $key;
        $this->value        = $value;
        $this->ttlInSeconds = $ttlInSeconds;
    }

    public function getKey(): Key
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getTtl(): int
    {
        return $this->ttlInSeconds;
    }
}
