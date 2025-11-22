# CallbackFilter

[← Back to README](../../README.md)

CallbackFilter decides whether to forward a log event by invoking a user callback. The callback receives `(level, message, context)` and must return `true` to pass or `false` to drop.

**When to use**

Use it for dynamic, content-based filtering such as whitelisting by keywords, dropping health probes, or enabling ad‑hoc debug windows.

**Examples**

```php
use Logger\Builder;
use Logger\Filters\CallbackFilter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$allowUsersOnly = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new CallbackFilter($l, fn (string $level, string $message, array $context): bool => str_contains($message, 'user:'))
);

$allowUsersOnly->info('user:42 updated');
$allowUsersOnly->info('system heartbeat'); // filtered out
```

Output:
```text
user:42 updated
```

# ExcludeLogLevelFilter

ExcludeLogLevelFilter drops all messages with a specific PSR‑3 level and forwards the rest.

**When to use**

Use it to suppress a single level globally (e.g., ignore noisy `debug` in production) without adjusting call sites.

**Examples**

```php
use Logger\Builder;
use Logger\Filters\ExcludeLogLevelFilter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new ExcludeLogLevelFilter($l, LogLevel::DEBUG)
);
$logger->debug('will not show');
$logger->info('visible');
```

Output:
```text
visible
```

# LogLevelRangeFilter

LogLevelRangeFilter forwards only messages whose level lies within the configured inclusive range `[min..max]`.

**When to use**

Use it to route messages by severity, for example, info–warning to STDOUT and error–emergency to STDERR.

**Examples**

```php
use Logger\Builder;
use Logger\Filters\LogLevelRangeFilter;
use Logger\Loggers\ResourceLogger;
use Logger\Loggers\LoggerCollection;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

$stdout = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new LogLevelRangeFilter($l, LogLevel::INFO, LogLevel::WARNING)
);
$stderr = Builder::chain(
    ResourceLogger::outputToStdErr(),
    fn (LoggerInterface $l) => new LogLevelRangeFilter($l, LogLevel::ERROR, LogLevel::EMERGENCY)
);

$logger = new LoggerCollection([$stdout, $stderr]);
$logger->notice('heads up');
$logger->error('boom');
```

Output (STDOUT):
```text
heads up
```

Output (STDERR):
```text
boom
```

# MaxLogLevelFilter

MaxLogLevelFilter forwards only messages at or below the specified maximum level.

**When to use**

Use it to capture less severe messages with one sink while allowing higher severities to be handled elsewhere.

**Examples**

```php
use Logger\Builder;
use Logger\Filters\MaxLogLevelFilter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new MaxLogLevelFilter($l, LogLevel::NOTICE)
);
$logger->info('included');
$logger->warning('filtered out');
```

Output:
```text
included
```

# MinLogLevelFilter

MinLogLevelFilter forwards only messages at or above the specified minimum level.

**When to use**

Use it to send warnings and errors to error channels while ignoring lower-severity noise.

**Examples**

```php
use Logger\Builder;
use Logger\Filters\MinLogLevelFilter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new MinLogLevelFilter($l, LogLevel::ERROR)
);
$logger->warning('ignored');
$logger->error('captured');
```

Output:
```text
captured
```

# RegularExpressionFilter

RegularExpressionFilter tests the message against a pattern and forwards it if it matches. You can provide modifiers and invert behavior with the `negate` flag.

**When to use**

Use it for quick substring or pattern matching when you need more than a simple callback but less than a full parser (e.g., only allow IDs, drop messages containing PII markers).

**Examples**

```php
use Logger\Builder;
use Logger\Filters\RegularExpressionFilter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$onlyIds = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new RegularExpressionFilter($l, 'ID-\\d+')
);
$onlyIds->info('ID-123 created');
$onlyIds->info('Account created'); // filtered out
```

Output:
```text
ID-123 created
```

```php
use Logger\Builder;
use Logger\Filters\RegularExpressionFilter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

// Negate=true: forward messages that DO NOT match the pattern
$noSecrets = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new RegularExpressionFilter($l, 'secret', 'u', true)
);
$noSecrets->warning('token leaked'); // filtered out
$noSecrets->warning('ok');
```

Output:
```text
ok
```
