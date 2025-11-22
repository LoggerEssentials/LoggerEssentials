# ExtendedLogger

ExtendedLogger augments the PSR‑3 interface with hierarchical captions and contextual execution scopes. It lets you build breadcrumb trails that become part of the message and consistently extend context across nested operations.

Note: `ExtendedPsrLoggerWrapper` is provided for backwards compatibility and currently extends `ExtendedLoggerImpl`. Prefer using `ExtendedLoggerImpl` directly for new code.

**When to use**

Use ExtendedLogger when you need structured call‑site context without repeating prefixes at each log call. It is ideal for batch processing, nested workflows, and request-scoped logging where you want to see the path a message took through the system.

**Examples**

```php
use Logger\Builder;
use Logger\Common\ExtendedLoggerImpl;
use Logger\Formatters\TemplateFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$base = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new TemplateFormatter($l)
);

$logger = new ExtendedLoggerImpl($base);

$orders = [1234567, 7654321, 4352617];
$proc = $logger->createSubLogger('Process order');
$proc->info('Start');

foreach ($orders as $orderId) {
    $child = $proc->createSubLogger((string) $orderId);
    $child->info('Start processing');
    $child->info('Successfully processed order');
}

$proc->info('Done');
```

Output:
```text
[2025-01-01T12:00:00+00:00] INFO       Process order > 1234567: Start processing - {}
[2025-01-01T12:00:00+00:00] INFO       Process order > 1234567: Successfully processed order - {}
[2025-01-01T12:00:00+00:00] INFO       Process order > 7654321: Start processing - {}
[2025-01-01T12:00:00+00:00] INFO       Process order > 7654321: Successfully processed order - {}
[2025-01-01T12:00:00+00:00] INFO       Process order > 4352617: Start processing - {}
[2025-01-01T12:00:00+00:00] INFO       Process order > 4352617: Successfully processed order - {}
[2025-01-01T12:00:00+00:00] INFO       Process order: Done - {}
```

```php
use Logger\Builder;
use Logger\Common\ExtendedLoggerImpl;
use Logger\Formatters\TemplateFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$base = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new TemplateFormatter($l)
);

$logger = new ExtendedLoggerImpl($base);

$logger->context(['a', 'b'], [], function (ExtendedLoggerImpl $logger) {
    $logger->context('c', [], function (ExtendedLoggerImpl $logger) {
        $logger->info('Test');
    });
});
```

Output:
```text
[2025-01-01T12:00:00+00:00] INFO       a > b > c: Test - {}
```

# ExtendedPsrLoggerWrapper

ExtendedPsrLoggerWrapper is a compatibility alias that currently inherits all behavior from `ExtendedLoggerImpl`.

**When to use**

Prefer `ExtendedLoggerImpl` for new projects. Use `ExtendedPsrLoggerWrapper` if you maintain existing code that already references this class.

**Examples**

```php
use Logger\Builder;
use Logger\Common\ExtendedPsrLoggerWrapper;
use Logger\Formatters\TemplateFormatter;
use Logger\Loggers\ResourceLogger;
use Psr\Log\LoggerInterface;

$base = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new TemplateFormatter($l)
);

$logger = new ExtendedPsrLoggerWrapper($base);
$logger->createSubLogger('Sub')->info('Hello World');
```

Output:
```text
[2025-01-01T12:00:00+00:00] INFO       Sub: Hello World - {}
```
