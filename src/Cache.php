<?php declare(strict_types=1);

namespace HarmonyIO\Cache;

use Amp\Promise;

interface Cache
{
    /**
     * @return Promise<string|null>
     */
    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.UselessDocComment
    public function get(Key $key): Promise;

    /**
     * @return Promise<bool>
     */
    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.UselessDocComment
    public function exists(Key $key): Promise;

    /**
     * @return Promise<null>
     */
    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.UselessDocComment
    public function delete(Key $key): Promise;

    /**
     * @return Promise<bool>
     */
    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.UselessDocComment
    public function store(Item $item): Promise;
}
