<?php declare(strict_types=1);

namespace HarmonyIO\Cache;

use Amp\Promise;

interface Cache
{
    /**
     * @return Promise<null|string>
     */
    public function get(Key $key): Promise;

    /**
     * @return Promise<bool>
     */
    public function exists(Key $key): Promise;

    /**
     * @return Promise<null>
     */
    public function delete(Key $key): Promise;

    /**
     * @return Promise<bool>
     */
    public function store(Item $item): Promise;
}
