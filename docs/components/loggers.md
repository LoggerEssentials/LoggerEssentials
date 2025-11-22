# ArrayLogger

ArrayLogger collects log entries in memory. Each call to `log()` stores the level, message, and context in an internal array that you can later inspect or clear. It does not write to I/O.

**When to use**

Use ArrayLogger for testing and inspection. It is ideal when you want to assert emitted logs in unit tests, or temporarily collect logs before forwarding them in bulk.

**Examples**

```php
use Logger\Builder;
use Logger\Loggers\ArrayLogger;

$logger = Builder::chain(new ArrayLogger());
$logger->info('User created', ['id' => 42]);
$logger->warning('Quota near limit');

var_export($logger->getMessages());
```

Output:
```text
array (
  0 => 
  array (
    'level' => 'info',
    'message' => 'User created',
    'context' => 
    array (
      'id' => 42,
    ),
  ),
  1 => 
  array (
    'level' => 'warning',
    'message' => 'Quota near limit',
    'context' => 
    array (
    ),
  ),
)
```

# CallbackLogger

CallbackLogger forwards each log event to a user-provided callable. The callable receives `(level, message, context)` and can relay the event to custom sinks (e.g., HTTP endpoints, queues, or in-memory counters).

**When to use**

Use CallbackLogger to integrate with systems that do not provide a PSR‑3 logger, or when you need full control over how log events are emitted without writing a new logger class.

**Examples**

```php
use Logger\Builder;
use Logger\Loggers\CallbackLogger;

$logger = Builder::chain(new CallbackLogger(function (string $level, string $message, array $context): void {
    printf("CB %s: %s\n", strtoupper($level), $message);
}));

$logger->notice('Callback works');
```

Output:
```text
CB NOTICE: Callback works
```

# ErrorLogLogger

ErrorLogLogger writes messages via PHP’s `error_log()` function. You may choose the `message_type` and optional destination/headers as supported by PHP.

**When to use**

Use ErrorLogLogger to write to the PHP error log, system log, or a specific file, especially in environments where standard error handling and rotation are already configured by the runtime or hosting platform.

**Examples**

```php
use Logger\Builder;
use Logger\Loggers\ErrorLogLogger;

// Default behavior, delegates to error_log($message)
$logger = Builder::chain(new ErrorLogLogger());
$logger->error('Application failure');
```

Output:
```text
Message appears in the PHP error log as configured by the environment.
```

```php
use Logger\Builder;
use Logger\Loggers\ErrorLogLogger;

// message_type=3 writes to a file path given in $destination
$file = sys_get_temp_dir() . '/app.log';
$logger = Builder::chain(new ErrorLogLogger(3, $file));
$logger->warning('Low disk space');
```

Output:
```text
The line 'Low disk space' is appended to the file at $file.
```

# LoggerCollection

LoggerCollection fans out a single log call to multiple PSR‑3 loggers. Use it to aggregate several outputs (e.g., console + file + remote) behind one interface.

**When to use**

Use LoggerCollection when the same log should be written to multiple destinations, or when combined with filters to route different severities to different outputs.

**Examples**

```php
use Logger\Builder;
use Logger\Loggers\LoggerCollection;
use Logger\Loggers\ResourceLogger;
use Logger\Filters\LogLevelRangeFilter;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

$stdout = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new LogLevelRangeFilter($l, LogLevel::INFO, LogLevel::WARNING)
);

$errors = Builder::chain(
    ResourceLogger::outputToStdErr(),
    fn (LoggerInterface $l) => new LogLevelRangeFilter($l, LogLevel::ERROR, LogLevel::EMERGENCY)
);

$logger = new LoggerCollection([$stdout, $errors]);

$logger->info('Hello world');
$logger->error('Something went wrong');
```

Output (STDOUT):
```text
Hello world
```

Output (STDERR):
```text
Something went wrong
```

# ResourceLogger

ResourceLogger writes the raw message to a given PHP stream resource. No formatting is applied; you control newlines in the message.

**When to use**

Use ResourceLogger to write directly to `STDOUT`, `STDERR`, or any custom stream (files, sockets, `php://` wrappers) when you don’t need extra formatting.

**Examples**

```php
use Logger\Builder;
use Logger\Loggers\ResourceLogger;

$logger = Builder::chain(ResourceLogger::outputToStdOut());
$logger->info("Plain line\n");
```

Output:
```text
Plain line
```

```php
use Logger\Builder;
use Logger\Loggers\ResourceLogger;

$fp = fopen('php://temp', 'wb+');
$logger = Builder::chain(new ResourceLogger($fp));
$logger->notice("Buffered log\n");
rewind($fp);
echo stream_get_contents($fp);
```

Output:
```text
Buffered log
```

# StreamLogger

StreamLogger opens a stream by URI (e.g., `php://stdout`, `/var/log/app.log`) and writes messages to it. It is a convenience wrapper over ResourceLogger that manages `fopen()` for you.

**When to use**

Use StreamLogger when you want to log to a specific URI or file path without manually opening the resource.

**Examples**

```php
use Logger\Builder;
use Logger\Loggers\StreamLogger;

$logger = Builder::chain(new StreamLogger('php://stdout'));
$logger->info("Hello via stream\n");
```

Output:
```text
Hello via stream
```

```php
use Logger\Builder;
use Logger\Loggers\StreamLogger;

$file = sys_get_temp_dir() . '/example.log';
$logger = Builder::chain(new StreamLogger($file, 'ab+'));
$logger->warning("Appended line\n");
echo file_get_contents($file);
```

Output:
```text
Appended line
```

# SyslogLogger

SyslogLogger sends messages to the system logger using `openlog()`/`syslog()`. It maps PSR‑3 levels to syslog severities and sets sensible defaults for `options` if none are provided.

**When to use**

Use SyslogLogger to integrate with the platform’s syslog daemon (e.g., journald, rsyslog) for centralized collection and rotation. It is particularly useful on servers and containers where syslog is monitored.

**Examples**

```php
use Logger\Builder;
use Logger\Loggers\SyslogLogger;

$logger = Builder::chain(new SyslogLogger('myapp'));
$logger->error('Failed to connect to database');
```

Output:
```text
Appears in syslog with ident 'myapp' (e.g., "myapp[1234]: Failed to connect to database").
```
