<?php declare(strict_types=1);

namespace HarmonyIO\CacheTest\Unit;

use HarmonyIO\Cache\Key;
use HarmonyIO\PHPUnitExtension\TestCase;

class KeyTest extends TestCase
{
    public function testGetType(): void
    {
        $this->assertSame(
            'HttpRequest',
            (new Key('HttpRequest', 'Validation::NotPwnedPassword', 'TheHash'))->getType(),
        );
    }

    public function testGetSource(): void
    {
        $this->assertSame(
            'Validation::NotPwnedPassword',
            (new Key('HttpRequest', 'Validation::NotPwnedPassword', 'TheHash'))->getSource(),
        );
    }

    public function testGetHash(): void
    {
        $this->assertSame(
            'TheHash',
            (new Key('HttpRequest', 'Validation::NotPwnedPassword', 'TheHash'))->getHash(),
        );
    }

    public function testToString(): void
    {
        $this->assertSame(
            'HarmonyIO_HttpRequest_Validation::NotPwnedPassword_TheHash',
            (string) new Key('HttpRequest', 'Validation::NotPwnedPassword', 'TheHash'),
        );
    }
}
