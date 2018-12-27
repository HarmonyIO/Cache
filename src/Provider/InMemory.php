<?php declare(strict_types=1);

namespace HarmonyIO\Cache\Provider;

use Amp\Cache\ArrayCache;
use Amp\Promise;
use HarmonyIO\Cache\Cache;
use HarmonyIO\Cache\Item;
use HarmonyIO\Cache\Key;
use function Amp\call;

class InMemory implements Cache
{
    /** @var ArrayCache */
    private $client;

    public function __construct()
    {
        $this->client = new ArrayCache();
    }

    public function get(Key $key): Promise
    {
        return $this->client->get((string) $key);
    }

    public function exists(Key $key): Promise
    {
        return call(function () use ($key) {
            $value = yield $this->get($key);

            return $value !== null;
        });
    }

    public function delete(Key $key): Promise
    {
        return call(function () use ($key) {
            yield $this->client->delete((string) $key);
        });
    }

    public function store(Item $item): Promise
    {
        return call(function () use ($item) {
            yield $this->client->set((string) $item->getKey(), $item->getValue(), $item->getTtl()->getTtlInSeconds());

            return true;
        });
    }
}
