<?php declare(strict_types=1);

namespace HarmonyIO\Cache;

use Amp\Promise;

interface Cache
{
    public function get(Key $key): Promise;

    public function exists(Key $key): Promise;

    public function delete(Key $key): Promise;

    public function store(Item $item): Promise;
}
