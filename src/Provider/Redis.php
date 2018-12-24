<?php declare(strict_types=1);

namespace HarmonyIO\Cache\Provider;

use Amp\Promise;
use Amp\Redis\Client;
use HarmonyIO\Cache\Cache;
use HarmonyIO\Cache\Item;
use HarmonyIO\Cache\Key;

class Redis implements Cache
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get(Key $key): Promise
    {
        return $this->client->get((string) $key);
    }

    public function exists(Key $key): Promise
    {
        return $this->client->exists((string) $key);
    }

    public function delete(Key $key): Promise
    {
        return $this->client->del((string) $key);
    }

    public function store(Item $item): Promise
    {
        return $this->client->set((string) $item->getKey(), $item->getValue(), $item->getTtl()->getTtlInSeconds());
    }
}
