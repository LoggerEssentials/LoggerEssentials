Logger‑Essentials compared to Apache log4php
============================================

[← Back to README](../../README.md)

High‑level differences

- Code‑driven vs config‑driven: Logger‑Essentials composes pipelines in PHP using small decorators (formatters, filters, extenders, outputs). log4php centers on configuration (XML/PHP), global category registries, appenders, layouts, and filters.
- PSR‑3 focus: Logger‑Essentials implements PSR‑3 `LoggerInterface` throughout. log4php predates PSR‑3 and uses its own API (adapters may exist in the ecosystem).
- Hierarchical context vs categories: `ExtendedLogger` provides per‑scope captions and nested execution scopes (breadcrumb‑style). log4php uses category trees with additivity to propagate to appenders.
- Routing: compose `LoggerCollection` and filters (min/max/range, regex, callbacks) in code; log4php routes via category inheritance, appender thresholds, and filter chains.
- Formatting: `TemplateFormatter` with placeholder/modifier pipeline vs log4php layouts like `PatternLayout`.
- Footprint and ergonomics: Logger‑Essentials aims for a small, modern PHP ≥ 8.0 surface; log4php offers a larger, configuration‑heavy framework with many appenders.

When to choose which

- Choose Logger‑Essentials for lightweight PSR‑3 pipelines, direct PHP composition, and hierarchical captions without a global logger registry.
- Choose log4php when you need its configuration model, existing appenders/layouts, or to integrate with legacy setups standardized on log4php.
