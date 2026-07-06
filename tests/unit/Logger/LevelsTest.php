<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Bridge\Psr3\Tests\Unit\Logger;

use DateTime;
use Phalcon\Bridge\Psr3\Logger;
use Phalcon\Bridge\Psr3\Tests\Support\Traits\SupportTrait;
use Phalcon\Logger\Adapter\Stream;
use PHPUnit\Framework\TestCase;

use function date;
use function end;
use function file_get_contents;
use function preg_match;

final class LevelsTest extends TestCase
{
    use SupportTrait;

    /**
     * @return string[]
     */
    public static function getExamples(): array
    {
        return [
            ['alert'],
            ['critical'],
            ['debug'],
            ['emergency'],
            ['error'],
            ['info'],
            ['notice'],
            ['warning'],
        ];
    }

    /**
     * Tests Phalcon\Logger :: alert()
     *
     * @dataProvider getExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testLoggerAlert(string $level)
    {
        $fileName   = $this->getNewFileName('log');
        $outputPath = $this->getLogsDirectory();
        $adapter  = new Stream($outputPath . $fileName);
        $logger   = new Logger('my-logger', ['one' => $adapter]);

        $logString = 'Hello';
        $logTime   = date('c');

        $logger->{$level}($logString);

        $logger->getAdapter('one')
               ->close()
        ;

        $content = file_get_contents($outputPath . $fileName);

        // Check if the $logString is in the log file
        $this->assertStringContainsString($logString, $content);

        // Check if the level is in the log file
        $this->assertStringContainsString(
            '[' . $level . ']',
            $content
        );

        // Check time content
        // Get time part
        $matches = [];
        preg_match(
            '/\[(.*)\]\[' . $level . '\]/',
            $content,
            $matches
        );
        $this->assertEquals(count($matches), 2);

        // Get Extract time
        $date             = end($matches);
        $logDateTime      = new DateTime($date);
        $dateTimeAfterLog = new DateTime($logTime);
        $nInterval        = $logDateTime->diff($dateTimeAfterLog)
                                        ->format('%s');
        $nSecondThreshold = 60;

        $this->assertLessThan($nSecondThreshold, $nInterval);

        $this->safeDeleteFile($outputPath . $fileName);
    }
}
