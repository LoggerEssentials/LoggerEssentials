# LogLevelCompressor

LogLevelCompressor clamps incoming log levels into a configured inclusive range. Messages below the minimum are raised to the minimum; messages above the maximum are lowered to the maximum. The text, context, and downstream pipeline are unchanged.

**When to use**

Use it to normalize noise: ensure everything below `INFO` is treated as `INFO` (for visibility), and everything above `CRITICAL` is treated uniformly (for quotas or alerting policies).

**Examples**

```php
use Logger\Builder;
use Logger\Manipulators\LogLevelCompressor;
use Logger\Formatters\TemplateFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new TemplateFormatter($l),
    fn (LoggerInterface $l) => new LogLevelCompressor($l, LogLevel::INFO, LogLevel::CRITICAL)
);

$logger->debug('Debug becomes INFO');
```

Output:
```text
[2025-01-01T12:00:00+00:00] INFO       Debug becomes INFO - {}
```

```php
use Logger\Builder;
use Logger\Manipulators\LogLevelCompressor;
use Logger\Formatters\TemplateFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

$logger = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new TemplateFormatter($l),
    fn (LoggerInterface $l) => new LogLevelCompressor($l, LogLevel::WARNING, LogLevel::ERROR)
);

$logger->notice('Raised to WARNING');
$logger->critical('Lowered to ERROR');
```

Output:
```text
[2025-01-01T12:00:00+00:00] WARNING    Raised to WARNING - {}
[2025-01-01T12:00:00+00:00] ERROR      Lowered to ERROR - {}
```
