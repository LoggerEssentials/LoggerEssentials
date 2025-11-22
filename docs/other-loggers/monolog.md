Logger‑Essentials compared to Monolog
=====================================

[← Back to README](../../README.md)

High‑level differences

- Composition‑first vs handler stacks: Logger‑Essentials builds a pipeline by chaining small PSR‑3 decorators (formatters, filters, extenders, outputs). Monolog centers on a `Logger` with a stack of Handlers (with bubbling) and Processors.
- Record model: Logger‑Essentials focuses on the PSR‑3 message + context and uses formatters to render; Monolog works with a rich record array (`channel`, `datetime`, `context`, `extra`, etc.) mutated by processors.
- Surface area and footprint: Logger‑Essentials intentionally keeps a compact component set; Monolog offers a broad ecosystem of handlers and integrations.
- Routing: Logger‑Essentials routes by composing `LoggerCollection` with filters (min/max/range, regex, callbacks). Monolog routes by handler levels and bubbling control.
- Scopes and captions: `ExtendedLogger` adds hierarchical captions and execution scopes for breadcrumb‑style output; in Monolog this is typically approximated via processors or message prefixes.
- Formatting model: `TemplateFormatter` provides placeholder + modifier pipelines (date, pad, uppercase, json, nobr, cut). Monolog offers `LineFormatter`, `JsonFormatter`, and others with different semantics.

When to choose which

- Choose Logger‑Essentials if you want a minimal, code‑driven pipeline, predictable single‑line output, and quick composition without external integrations.
- Choose Monolog if you need its extensive handlers (e.g., Slack, Sentry, Rollbar, Elastic, native syslog variants), existing framework integrations, or its processor‑driven structured records.
