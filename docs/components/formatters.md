# CallbackFormatter

[← Back to README](../../README.md)

CallbackFormatter transforms the message via a user-provided callable before forwarding to the next logger. The callable receives `(level, message, context)` and must return the new message string.

**When to use**

Use it to implement custom formatting rules quickly (e.g., title-casing, localization, or dynamic message enrichment) without writing a new class.

**Examples**

```php
use Logger\Builder;
use Logger\Formatters\CallbackFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new CallbackFormatter(
        $l,
        fn (string $level, string $message, array $context): string => strtoupper($message)
    )
);

$logger->info('hello world');
```

Output:
```text
HELLO WORLD
```

# ContextJsonFormatter

ContextJsonFormatter appends the JSON-encoded context to the message using a configurable `sprintf`-style format (default `"%s %s"`). Empty contexts render as `{}`.

**When to use**

Use it when you want structured, machine-readable context attached to each line while keeping the human-readable message intact.

**Examples**

```php
use Logger\Builder;
use Logger\Formatters\ContextJsonFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new ContextJsonFormatter($l)
);
$logger->notice('User created', ['id' => 42, 'role' => 'admin']);
```

Output:
```text
User created {"id":42,"role":"admin"}
```

# DateTimeFormatter

DateTimeFormatter prepends a formatted date/time to each message. Both the date format and the overall `sprintf`-style output format are configurable.

**When to use**

Use it when you need timestamps but prefer a simpler alternative to full templating. It pairs well with other formatters.

**Examples**

```php
use Logger\Builder;
use Logger\Formatters\DateTimeFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new DateTimeFormatter($l, '[Y-m-d H:i:s] ', '%s%s')
);
$logger->info('Startup complete');
```

Output:
```text
[2025-01-01 12:00:00] Startup complete
```

# FormatFormatter

FormatFormatter wraps the message in a single `sprintf` pattern. It replaces one `%s` with the original message.

**When to use**

Use it to add static decorations to messages (prefixes/suffixes) without other metadata.

**Examples**

```php
use Logger\Builder;
use Logger\Formatters\FormatFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new FormatFormatter($l, '[APP] %s')
);
$logger->warning('Near quota');
```

Output:
```text
[APP] Near quota
```

# MaxLengthFormatter

MaxLengthFormatter truncates messages that exceed a specified length, appending an ellipsis (default `...`). It is multibyte-aware (`mb_*`) and supports custom charset.

**When to use**

Use it to enforce line-length limits for sinks that reject or wrap overly long messages (e.g., syslog, dashboards, or UIs).

**Examples**

```php
use Logger\Builder;
use Logger\Formatters\MaxLengthFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new MaxLengthFormatter($l, 12)
);
$logger->info('Supercalifragilisticexpialidocious');
```

Output:
```text
Supercal...
```

# MessagePrefixFormatter

MessagePrefixFormatter adds a caption to the message. The caption can be a string or a list of parts joined by a concatenator (default `' > '`) and terminated by an ending concatenator (default `': '`).

**When to use**

Use it to consistently label messages by feature, subsystem, or request scope without relying on extended loggers.

**Examples**

```php
use Logger\Builder;
use Logger\Formatters\MessagePrefixFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new MessagePrefixFormatter($l, 'AddCustomer')
);
$logger->notice('Started');
```

Output:
```text
AddCustomer: Started
```

```php
use Logger\Builder;
use Logger\Formatters\MessagePrefixFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new MessagePrefixFormatter($l, ['Checkout', 'Payment'], ' > ', ': ')
);
$logger->info('Authorized');
```

Output:
```text
Checkout > Payment: Authorized
```

# NobrFormatter

NobrFormatter replaces any newline sequences in the message with a single replacement character (default space). It keeps each log line on a single physical line.

**When to use**

Use it when your sink expects one-line entries (e.g., log aggregation, CSV exports) or to avoid multi-line stack traces breaking the format.

**Examples**

```php
use Logger\Builder;
use Logger\Formatters\NobrFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new NobrFormatter($l)
);
$logger->warning("Line1\nLine2\r\nLine3");
```

Output:
```text
Line1 Line2 Line3
```

# PassThroughFormatter

PassThroughFormatter forwards the message unchanged. It exists to keep pipeline composition uniform when a stage is optional.

**When to use**

Use it as a placeholder where a formatter might be injected conditionally, or for testing pipeline ordering.

**Examples**

```php
use Logger\Builder;
use Logger\Formatters\PassThroughFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new PassThroughFormatter($l)
);
$logger->info('Exactly as written');
```

Output:
```text
Exactly as written
```

# ReplaceFormatter

ReplaceFormatter performs a simple `strtr` replacement on the message using a provided map.

**When to use**

Use it to redact or normalize known tokens in messages (e.g., replace secret values or noisy substrings) without regex overhead.

**Examples**

```php
use Logger\Builder;
use Logger\Formatters\ReplaceFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new ReplaceFormatter($l, ['password' => '******', 'token=' => 'token=<redacted>'])
);
$logger->error('Login failed: password=abc token=123');
```

Output:
```text
Login failed: ******=abc token=<redacted>123
```

# TemplateFormatter

TemplateFormatter renders rich, structured log lines from a format string with placeholders and modifiers. By default it uses:
`"[%now|date:c%] %level|lpad:10|uppercase% %message|nobr% %ip|default:\"-\"% %context|json%\n"`.

Placeholders like `%message%` can be piped through modifiers such as `date`, `uppercase`, `trim`, `nobr`, `json`, `pad/lpad/rpad`, `lowercase`, `ucfirst`, `ucwords`, `cut`, and `default`.

**When to use**

Use TemplateFormatter when you need consistent, enriched lines suitable for both humans and machines, including timestamps, padded levels, and JSON context with exception normalization.

**Examples**

```php
use Logger\Builder;
use Logger\Formatters\TemplateFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new TemplateFormatter($l)
);
$logger->error('Failed to fetch', ['id' => 42]);
```

Output:
```text
[2025-01-01T12:00:00+00:00] ERROR      Failed to fetch - {"id":42}
```

```php
use Logger\Builder;
use Logger\Formatters\TemplateFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$format = '[%now|date:Y-m-d H:i:s%] %level|uppercase% %message|trim% %context|json%\n';
$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new TemplateFormatter($l, $format, ['ip' => '127.0.0.1'])
);
$logger->notice('  Ok  ', ['feature' => 'auth']);
```

Output:
```text
[2025-01-01 12:00:00] NOTICE Ok {"feature":"auth"}
```

# TrimFormatter

TrimFormatter trims leading and trailing whitespace from the message.

**When to use**

Use it to clean up messages produced by external sources or templates where whitespace is inconsistent.

**Examples**

```php
use Logger\Builder;
use Logger\Formatters\TrimFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new TrimFormatter($l)
);
$logger->info("  spaced  ");
```

Output:
```text
spaced
```
