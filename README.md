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

## Development

The repository ships a Docker setup for local development and testing. You only need Docker +
Docker Compose; the PHP runtime and Phalcon are provided inside the container.

### Quick start

```bash
docker compose up -d --build
docker compose exec app composer install
docker compose exec app composer test
```

> `app` is the Compose *service* name. The running container is `bridge-psr3-app` (override with
> `PROJECT_PREFIX`). It stays up via a `sleep infinity` keepalive, so you can
> `docker compose exec app <cmd>` freely (e.g. `composer update`).

### Choosing the PHP version

The image is built for one PHP version at a time, selected with the `PHP_VERSION` build arg
(default `8.5`; supported `8.1`–`8.5`). Because it is a **build** arg, changing it requires a
rebuild (`--build`):

```bash
docker compose up -d --build                  # PHP 8.5 (default)
PHP_VERSION=8.1 docker compose up -d --build  # PHP 8.1
PHP_VERSION=8.4 docker compose up -d --build  # PHP 8.4
```

The container keeps the same name, so each rebuild **replaces** the previous one. To run several
versions side by side, give each its own Compose project and prefix:

```bash
PHP_VERSION=8.1 PROJECT_PREFIX=bridge-psr3-81 docker compose -p bridge-psr3-81 up -d --build
# then: docker exec -w /srv bridge-psr3-81-app composer test
```

### Choosing the backend

The bridge works against either Phalcon runtime, selected with the `PHALCON_VARIANT` build arg:

```bash
docker compose up -d --build                     # package: phalcon/phalcon (v6, default)
PHALCON_VARIANT=ext docker compose up -d --build  # ext: cphalcon C extension (v5)
```

Tip: drop `PHP_VERSION` / `PHALCON_VARIANT` into a `.env` file in the repo root to avoid prefixing
every command — Compose reads it automatically.

### Composer scripts

Run them inside the container, e.g. `docker compose exec app composer cs`:

| Script | Description |
| --- | --- |
| `composer cs` | PHP_CodeSniffer (PSR-12) |
| `composer cs-fix` | Auto-fix coding-standard issues (phpcbf) |
| `composer cs-fixer` | PHP CS Fixer (dry-run) |
| `composer cs-fixer-fix` | Apply PHP CS Fixer |
| `composer analyze` | PHPStan static analysis |
| `composer test` / `composer test-unit` | Unit tests via [`phalcon/talon`](https://github.com/phalcon/talon) |
| `composer test-coverage` | Tests + Clover coverage (`tests/_output/coverage.xml`) |

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
[ci-badge]:             https://github.com/phalcon/bridge-psr3/actions/workflows/main.yml/badge.svg?branch=master
[ci-link]:              https://github.com/phalcon/bridge-psr3/actions/workflows/main.yml
[sonar-quality-badge]:  https://sonarcloud.io/api/project_badges/measure?project=phalcon_bridge-psr3&metric=alert_status
[sonar-coverage-badge]: https://sonarcloud.io/api/project_badges/measure?project=phalcon_bridge-psr3&metric=coverage
[sonar-link]:           https://sonarcloud.io/summary/new_code?id=phalcon_bridge-psr3
[pds-skeleton-badge]:   https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat-square
[pds-skeleton-link]:    https://github.com/php-pds/skeleton
[discord-badge]:        https://img.shields.io/discord/310910488152375297?label=Discord&logo=discord&style=flat-square
