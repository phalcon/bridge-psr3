<?php

declare(strict_types=1);

namespace Phalcon\Bridge\Psr3\Tests\Unit\Adapter;

use Phalcon\Bridge\Psr3\Adapter;
use Phalcon\Bridge\Psr3\Tests\Support\InMemoryLogger;
use Phalcon\Logger\Enum;
use Phalcon\Logger\Logger as PhalconLogger;
use PHPUnit\Framework\TestCase;

final class ProcessTest extends TestCase
{
    public static function getStandardLevels(): array
    {
        return [
            ['emergency'],
            ['alert'],
            ['critical'],
            ['error'],
            ['warning'],
            ['notice'],
            ['info'],
            ['debug'],
        ];
    }

    /**
     * @dataProvider getStandardLevels
     */
    public function testForwardsStandardLevel(string $level): void
    {
        $double = new InMemoryLogger();
        $logger = new PhalconLogger('test', ['psr' => new Adapter($double)]);

        $logger->{$level}('Hello', ['k' => 'v']);

        $this->assertCount(1, $double->records);
        $this->assertSame($level, $double->records[0]['level']);
        $this->assertSame('Hello', $double->records[0]['message']);
        $this->assertSame(['k' => 'v'], $double->records[0]['context']);
    }

    public function testMapsTraceToDebug(): void
    {
        $double = new InMemoryLogger();
        $logger = new PhalconLogger('test', ['psr' => new Adapter($double)]);
        $logger->setLogLevel(Enum::TRACE);

        $logger->trace('t');

        $this->assertSame('debug', $double->records[0]['level']);
    }

    public function testMapsCustomToDebug(): void
    {
        $double = new InMemoryLogger();
        $logger = new PhalconLogger('test', ['psr' => new Adapter($double)]);

        $logger->log(Enum::CUSTOM, 'c');

        $this->assertSame('debug', $double->records[0]['level']);
    }
}
