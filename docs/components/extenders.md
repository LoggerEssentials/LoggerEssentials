# CallbackExtender

CallbackExtender invokes a user-provided callback for every log event before passing it on. It does not alter the message or context itself; it is meant for side-effects such as metrics, tracing, or custom enrichment performed out-of-band.

**When to use**

Use it to emit counters, traces, or to notify external observers whenever a log occurs, without modifying the log pipeline or message content.

**Examples**

```php
use Logger\Builder;
use Logger\Extenders\CallbackExtender;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$counter = 0;
$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new CallbackExtender(
        $l,
        function (string $level, string $message, array $context) use (&$counter): void {
            $counter++;
        }
    )
);

$logger->info('Hello');
echo "count=$counter\n";
```

Output:
```text
Hello
count=1
```

# ContextExtender

ContextExtender adds predefined key/value pairs to the context of every log entry. Objects are stringified via `__toString()` when available, otherwise JSON‑encoded.

**When to use**

Use it when cross-cutting context (request IDs, tenant, user, deployment info) should appear consistently on all logs without changing call sites.

**Examples**

```php
use Logger\Builder;
use Logger\Extenders\ContextExtender;
use Logger\Formatters\ContextJsonFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new ContextJsonFormatter($l),
    fn (LoggerInterface $l) => new ContextExtender($l, ['requestId' => 'abc-123', 'service' => 'billing'])
);
$logger->notice('Charge started');
```

Output:
```text
Charge started {"requestId":"abc-123","service":"billing"}
```

# StacktraceExtender

StacktraceExtender captures the current PHP stack trace and attaches it to the log context under a configurable key (default `stacktrace`). You can pass `debug_backtrace` flags to control argument collection.

**When to use**

Use it to aid debugging and observability when you need to know where a log originated, especially in complex or asynchronous flows.

**Examples**

```php
use Logger\Builder;
use Logger\Extenders\StacktraceExtender;
use Logger\Formatters\ContextJsonFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new ContextJsonFormatter($l),
    fn (LoggerInterface $l) => new StacktraceExtender($l)
);
$logger->warning('Unexpected state');
```

Output:
```text
Unexpected state {"stacktrace":[{"file":"/path/to/file.php","line":123, ...}]}
```
