<?php

declare(strict_types=1);

namespace Phalcon\Bridge\Psr3\Tests\Support;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;

final class InMemoryLogger implements LoggerInterface
{
    use LoggerTrait;

    /** @var array<int, array{level: mixed, message: string, context: array}> */
    public array $records = [];

    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->records[] = [
            'level'   => $level,
            'message' => (string) $message,
            'context' => $context,
        ];
    }
}
