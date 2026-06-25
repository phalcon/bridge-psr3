<?php

declare(strict_types=1);

namespace Phalcon\Bridge\Psr3\Tests\Unit\Adapter;

use Phalcon\Bridge\Psr3\Adapter;
use Phalcon\Bridge\Psr3\Tests\Support\InMemoryLogger;
use PHPUnit\Framework\TestCase;

final class CloseTest extends TestCase
{
    public function testCloseReturnsTrue(): void
    {
        $adapter = new Adapter(new InMemoryLogger());

        $this->assertTrue($adapter->close());
    }
}
