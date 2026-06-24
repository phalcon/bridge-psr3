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
use Phalcon\Logger\Enum;
use PHPUnit\Framework\TestCase;

final class GetSetLogLevelTest extends TestCase
{
    /**
     * Tests Phalcon\Logger :: getLogLevel()/setLogLevel()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerGetSetLogLevel()
    {
        $logger = new Logger('my-name');

        $this->assertEquals(Enum::CUSTOM, $logger->getLogLevel());

        $object = $logger->setLogLevel(Enum::INFO);
        $this->assertInstanceOf(Logger::class, $object);

        $this->assertEquals(Enum::INFO, $logger->getLogLevel());

        $logger->setLogLevel(99);
        $this->assertEquals(Enum::CUSTOM, $logger->getLogLevel());
    }
}
