# Phalcon Framework - Bridge PSR-3

[![Bridge PSR-3 CI][ci-badge]][ci-link]
[![Quality Gate Status][sonar-quality-badge]][sonar-link]
[![Coverage][sonar-coverage-badge]][sonar-link]
[![PDS Skeleton][pds-skeleton-badge]][pds-skeleton-link]

Phalcon is an open source web framework delivered as a C extension for the PHP language providing high performance and lower resource consumption.

Bridge PSR-3 connects the Phalcon logger and the PSR-3 (`Psr\Log\LoggerInterface`) standard in both directions:

* **`Logger`** - a PSR-3 logger backed by Phalcon's logging adapters. Use it wherever a `Psr\Log\LoggerInterface` is expected.
* **`Adapter`** - a Phalcon log adapter that forwards to a PSR-3 logger. Use it to make any PSR-3 logger (e.g. Monolog) act as a Phalcon log target.

## Installation

You can install the package using composer

```sh
composer require phalcon/bridge-psr3
```

## Usage

### `Logger` - use Phalcon logging through a PSR-3 interface

`Phalcon\Bridge\Psr3\Logger` *is* a `Psr\Log\LoggerInterface`, configured with
Phalcon logging adapters. Hand it to any code that expects a PSR-3 logger.

```php
use Phalcon\Bridge\Psr3\Logger;
use Phalcon\Logger\Adapter\Stream;

$logger = new Logger(
    'my-app',
    [
        'main' => new Stream('/var/log/app.log'),
    ]
);

// $logger is a Psr\Log\LoggerInterface
$logger->info('User logged in', ['id' => 42]);
$logger->error('Payment failed');
```

### `Adapter` - use a PSR-3 logger as a Phalcon log target

`Phalcon\Bridge\Psr3\Adapter` is a Phalcon log adapter that forwards to a
wrapped PSR-3 logger. Add it to a `Phalcon\Logger\Logger` and inject that
wherever Phalcon expects a logger.

```php
use Phalcon\Bridge\Psr3\Adapter;
use Phalcon\Logger\Logger;

// Any Psr\Log\LoggerInterface, e.g. Monolog
$psr = new Monolog\Logger('my-app');

$logger = new Logger(
    'my-app',
    [
        'psr' => new Adapter($psr),
    ]
);

// Phalcon log calls now flow into the PSR-3 logger
$logger->warning('Low disk space');

// e.g. inject into the DataMapper profiler, which expects a Phalcon logger
$profiler = new Phalcon\DataMapper\Pdo\Profiler\Profiler($logger);
```

## Links

### General
* [Official Documentation](https://docs.phalcon.io/)

### Support
* [Forum](https://phalcon.io/forum)
* [Discord](https://phalcon.io/discord)
* [Stack Overflow](https://phalcon.io/so)

### Social Media
* [Telegram](https://phalcon.io/telegram)
* [Gab](https://phalcon.io/gab)
* [LinkedIn](https://phalcon.io/linkedin)
* [MeWe](https://phalcon.io/mewe)
* [Facebook](https://phalcon.io/fb)
* [Twitter](https://phalcon.io/t)


<!-- External links should be here -->
[ci-badge]:             https://github.com/phalcon/bridge-psr3/actions/workflows/main.yml/badge.svg?branch=main
[ci-link]:              https://github.com/phalcon/bridge-psr3/actions/workflows/main.yml
[sonar-quality-badge]:  https://sonarcloud.io/api/project_badges/measure?project=phalcon_bridge-psr3&metric=alert_status
[sonar-coverage-badge]: https://sonarcloud.io/api/project_badges/measure?project=phalcon_bridge-psr3&metric=coverage
[sonar-link]:           https://sonarcloud.io/summary/new_code?id=phalcon_bridge-psr3
[pds-skeleton-badge]:   https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat-square
[pds-skeleton-link]:    https://github.com/php-pds/skeleton
[discord-badge]:        https://img.shields.io/discord/310910488152375297?label=Discord&logo=discord&style=flat-square
