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

See full [Documentation](docs/index.md) for details.

How it differs from KLogger

- Composition-first design: build a pipeline from small pieces (formatters, filters, extenders, outputs) rather than configuring a single monolithic logger.
- Multiple outputs and routing: fan-out to many sinks and route by level or rules; mix `STDOUT`, `STDERR`, syslog, files, callbacks, and in-memory collectors.
- Rich formatting: `TemplateFormatter` with placeholders and modifiers, plus a suite of focused formatters (datetime, truncation, prefixes, JSON context, trimming, nobr).
- Context and scopes: `ExtendedLogger` adds hierarchical captions and execution scopes for breadcrumb-like context without repeating prefixes.
- First-class filters/extenders: drop/allow by callbacks, regex, or level ranges; attach stacktraces or constant context keys without changing call sites.
- Modern PHP ergonomics: targets PHP ≥ 8.0 with type declarations and static analysis in mind.

See the dedicated note: docs/other-loggers/klogger.md

How it differs from Monolog

- Lean, pipeline primitives: compose behavior by chaining tiny PSR‑3 decorators instead of configuring a central Logger with handlers/processors.
- Simple record shape: primarily message + context, enriched via formatters; Monolog uses structured record arrays (channel, datetime, context, extra) and processors.
- Focused surface area: small set of components vs Monolog’s large handler ecosystem; easier to reason about for library/CLI use.
- Routing by composition: use `LoggerCollection` plus filters (min/max/range, regex, callbacks) to fan‑out and route; Monolog uses handler stacks with bubbling/level thresholds.
- Built‑in hierarchical scopes: `ExtendedLogger` for captions and nested scopes; Monolog typically achieves similar output via processors/prefixes.
- When to choose: prefer Logger‑Essentials for minimal footprint and code‑driven pipelines; prefer Monolog when you need its broad integrations (Slack, Sentry, syslog variants, etc.).

See the dedicated note: docs/other-loggers/monolog.md

How it differs from log4php

- Code‑driven vs config‑driven: compose pipelines in PHP with small PSR‑3 decorators instead of XML/PHP configuration files, category registries, and global appenders.
- PSR‑3 first: exposes the standard `LoggerInterface`; log4php predates PSR‑3 and uses its own API style (often bridged via adapters).
- Hierarchy model: use `ExtendedLogger` for lightweight, per‑call‑site scopes/captions instead of global category trees and additivity.
- Routing: combine `LoggerCollection` with filters (min/max/range, regex, callbacks) vs log4php’s appender thresholds and category inheritance.
- Formatting: `TemplateFormatter` with placeholder/modifier pipeline vs log4php layouts (e.g., `PatternLayout`).
- When to choose: prefer Logger‑Essentials for minimal footprint, PHP 8 composition, and PSR‑3 pipelines; prefer log4php when you need its configuration‑heavy ecosystem and established appender/layout model.

See the dedicated note: docs/other-loggers/log4php.md
