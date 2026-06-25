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

namespace Phalcon\Bridge\Psr3;

use Phalcon\Logger\Adapter\AbstractAdapter;
use Phalcon\Logger\Item;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

use function strtolower;

/**
 * Phalcon Bridge PSR-3 Adapter.
 *
 * A Phalcon log adapter that forwards log items to a wrapped PSR-3 logger.
 */
class Adapter extends AbstractAdapter
{
    public function __construct(protected LoggerInterface $logger)
    {
    }

    public function close(): bool
    {
        return true;
    }

    public function process(Item $item): void
    {
        $this->logger->log(
            $this->toPsrLevel($item->getLevelName()),
            $item->getMessage(),
            $item->getContext()
        );
    }

    private function toPsrLevel(string $levelName): string
    {
        return match (strtolower($levelName)) {
            'emergency' => LogLevel::EMERGENCY,
            'alert'     => LogLevel::ALERT,
            'critical'  => LogLevel::CRITICAL,
            'error'     => LogLevel::ERROR,
            'warning'   => LogLevel::WARNING,
            'notice'    => LogLevel::NOTICE,
            'info'      => LogLevel::INFO,
            'debug'     => LogLevel::DEBUG,
            default     => LogLevel::DEBUG,
        };
    }
}
