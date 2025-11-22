Logger‑Essentials compared to KLogger
=====================================

[← Back to README](../../README.md)

High‑level differences

- Composition‑first: Logger‑Essentials is built from small, chainable components (formatters, filters, extenders, outputs). KLogger takes a more single‑logger configuration approach.
- Multiple sinks and routing: fan‑out to several outputs and route by severity/rules (e.g., info–warning to STDOUT, errors to STDERR/syslog).
- Rich formatting: `TemplateFormatter` with placeholders/modifiers and focused utilities (date/time, prefixes, truncation, JSON context, trimming, nobr).
- Context and scopes: `ExtendedLogger` provides hierarchical captions and execution scopes for breadcrumb‑like context.
- First‑class filters/extenders: level range/threshold filters, regex and callback filters, plus extenders to add stacktraces or constant context keys.
- Modern PHP: targets PHP ≥ 8.0 with type declarations and static analysis support.

Notes

- If you are happy with KLogger’s API and primarily need file‑based logging with minimal dependencies, KLogger remains a solid option.
- Choose Logger‑Essentials when you want composable pipelines, richer formatting, multi‑sink routing, or hierarchical log context without repeating prefixes.
