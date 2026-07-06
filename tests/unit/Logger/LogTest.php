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

use Phalcon\Bridge\Psr3\Logger;
use Phalcon\Bridge\Psr3\Tests\Support\Traits\SupportTrait;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\Enum;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function sprintf;

final class LogTest extends TestCase
{
    use SupportTrait;

    /**
     * Tests Phalcon\Logger :: log()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerLog()
    {
        $outputPath  = $this->getLogsDirectory();
        $fileName = $this->getNewFileName('log');
        $adapter  = new Stream($outputPath . $fileName);

        $logger = new Logger(
            'my-logger',
            [
                'one' => $adapter,
            ]
        );

        $levels = [
            Enum::ALERT     => 'alert',
            Enum::CRITICAL  => 'critical',
            Enum::DEBUG     => 'debug',
            Enum::EMERGENCY => 'emergency',
            Enum::ERROR     => 'error',
            Enum::INFO      => 'info',
            Enum::NOTICE    => 'notice',
            Enum::WARNING   => 'warning',
            Enum::CUSTOM    => 'custom',
            'alert'           => 'alert',
            'critical'        => 'critical',
            'debug'           => 'debug',
            'emergency'       => 'emergency',
            'error'           => 'error',
            'info'            => 'info',
            'notice'          => 'notice',
            'warning'         => 'warning',
            'custom'          => 'custom',
        ];

        foreach ($levels as $level => $levelName) {
            $logger->log($level, 'Message ' . $levelName);
        }

        $contents = file_get_contents($outputPath . $fileName);

        foreach ($levels as $levelName) {
            $expected = sprintf(
                '[%s] Message %s',
                $levelName,
                $levelName
            );

            $this->assertStringContainsString($expected, $contents);
        }

        $adapter->close();
        $this->safeDeleteFile($outputPath . $fileName);
    }

    /**
     * Tests Phalcon\Logger :: log() - logLevel
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerLogLogLevel()
    {
        $outputPath  = $this->getLogsDirectory();
        $fileName = $this->getNewFileName('log');
        $adapter  = new Stream($outputPath . $fileName);

        $logger = new Logger(
            'my-logger',
            [
                'one' => $adapter,
            ]
        );

        $logger->setLogLevel(Enum::ALERT);

        $levelsYes = [
            Enum::ALERT     => 'alert',
            Enum::CRITICAL  => 'critical',
            Enum::EMERGENCY => 'emergency',
            'alert'           => 'alert',
            'critical'        => 'critical',
            'emergency'       => 'emergency',
        ];

        $levelsNo = [
            Enum::DEBUG   => 'debug',
            Enum::ERROR   => 'error',
            Enum::INFO    => 'info',
            Enum::NOTICE  => 'notice',
            Enum::WARNING => 'warning',
            Enum::CUSTOM  => 'custom',
            'debug'         => 'debug',
            'error'         => 'error',
            'info'          => 'info',
            'notice'        => 'notice',
            'warning'       => 'warning',
            'custom'        => 'custom',
        ];

        foreach ($levelsYes as $level => $levelName) {
            $logger->log($level, 'Message ' . $levelName);
        }

        foreach ($levelsNo as $level => $levelName) {
            $logger->log($level, 'Message ' . $levelName);
        }

        $contents = file_get_contents($outputPath . $fileName);

        foreach ($levelsYes as $levelName) {
            $expected = sprintf(
                '[%s] Message %s',
                $levelName,
                $levelName
            );
            $this->assertStringContainsString($expected, $contents);
        }

        foreach ($levelsNo as $levelName) {
            $expected = sprintf(
                '[%s] Message %s',
                $levelName,
                $levelName
            );
            $this->assertStringNotContainsString($expected, $contents);
        }

        $adapter->close();
        $this->safeDeleteFile($outputPath . $fileName);
    }
}
