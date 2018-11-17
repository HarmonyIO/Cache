<?php declare(strict_types=1);

namespace HarmonyIO\CacheTest\Unit;

use HarmonyIO\Cache\Item;
use HarmonyIO\Cache\Key;
use HarmonyIO\PHPUnitExtension\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ItemTest extends TestCase
{
    /** @var Item */
    private $item;

    /** @var MockObject|Key */
    private $key;

    public function setUp(): void
    {
        $this->key = $this->createMock(Key::class);

        $this->item = new class($this->key) extends Item
        {
            public function __construct(Key $key)
            {
                parent::__construct($key, 'TheValue', 10);
            }
        };
    }

    public function testGetKey(): void
    {
        $this->assertSame($this->key, $this->item->getKey());
    }

    public function testGetValue(): void
    {
        $this->assertSame('TheValue', $this->item->getValue());
    }

    public function testGetTtl(): void
    {
        $this->assertSame(10, $this->item->getTtl());
    }

    public function testGetDefaultTtl(): void
    {
        $item = new class($this->key) extends Item
        {
            public function __construct(Key $key)
            {
                parent::__construct($key, 'TheValue');
            }
        };

        $this->assertSame(0, $item->getTtl());
    }
}
