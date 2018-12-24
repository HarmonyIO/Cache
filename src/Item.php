<?php declare(strict_types=1);

namespace HarmonyIO\Cache;

class Item
{
    /** @var Key */
    private $key;

    /** @var string */
    private $value;

    /** @var Ttl */
    private $ttl;

    public function __construct(Key $key, string $value, ?Ttl $ttl = null)
    {
        if ($ttl === null) {
            $ttl = new Ttl(0);
        }

        $this->key   = $key;
        $this->value = $value;
        $this->ttl   = $ttl;
    }

    public function getKey(): Key
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getTtl(): Ttl
    {
        return $this->ttl;
    }
}
