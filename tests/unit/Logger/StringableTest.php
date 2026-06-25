<?php

declare(strict_types=1);

namespace Phalcon\Bridge\Psr3\Tests\Unit\Logger;

use Phalcon\Bridge\Psr3\Logger;
use Phalcon\Bridge\Psr3\Tests\Support\Traits\SupportTrait;
use Phalcon\Logger\Adapter\Stream;
use PHPUnit\Framework\TestCase;
use Stringable;

use function file_get_contents;

final class StringableTest extends TestCase
{
    use SupportTrait;

    public function testLogsStringableMessage(): void
    {
        $fileName   = $this->getNewFileName('log');
        $outputPath = $this->getLogsDirectory();
        $adapter    = new Stream($outputPath . $fileName);
        $logger     = new Logger('my-logger', ['one' => $adapter]);

        $message = new class implements Stringable {
            public function __toString(): string
            {
                return 'stringified message';
            }
        };

        $logger->info($message);
        $logger->getAdapter('one')->close();

        $content = file_get_contents($outputPath . $fileName);

        $this->assertStringContainsString('stringified message', $content);

        $this->safeDeleteFile($outputPath . $fileName);
    }
}
