logger/essentials
=====================

[![Latest Stable Version](https://poser.pugx.org/logger/essentials/version.svg)](https://packagist.org/packages/logger/essentials)
[![License](https://poser.pugx.org/logger/essentials/license.svg)](https://packagist.org/packages/logger/essentials)

A lightweight, composable logging toolkit for PHP that implements the PSR‑3 LoggerInterface and lets you build flexible pipelines. Combine output loggers (e.g., streams, syslog) with formatters, filters, and extenders to shape messages, enrich context, and route by severity. Includes an ExtendedLogger for hierarchical captions and execution scopes.

Quick taste

```php
use Logger\Builder;
use Logger\Formatters\TemplateFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new TemplateFormatter($l)
);

$logger->info('Hello world', ['id' => 42]);
```

Component overview

- [Common](docs/components/common.md) — ExtendedLogger for hierarchical captions and contextual scopes.
- [Formatters](docs/components/formatters.md) — Render and transform messages (Template, DateTime, Prefix, JSON, etc.).
- [Filters](docs/components/filters.md) — Pass/drop by rules (min/max/range level, callbacks, regex).
- [Loggers](docs/components/loggers.md) — Output sinks: streams, syslog, callbacks, in‑memory array.
- [Extenders](docs/components/extenders.md) — Side‑effect hooks and enrichment (context, stacktrace, metrics).
- [Manipulators](docs/components/manipulators.md) — Adjust semantics like compressing log levels.

How it differs from other logging solutions:

[Monolog](docs/other-loggers/monolog.md)
[KLogger](docs/other-loggers/klogger.md)
[Log4PHP](docs/other-loggers/log4php.md)
