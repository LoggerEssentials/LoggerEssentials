# Getting started

```php
use Logger\Builder;
use Logger\Loggers\LoggerCollection;
use Logger\Loggers\ResourceLogger;
use Logger\Formatters\TemplateFormatter;
use Logger\Filters\MaxLogLevelFilter;
use Logger\Filters\MinLogLevelFilter;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

$stdout = Builder::chain(
    ResourceLogger::outputToStdOut(),
    fn (LoggerInterface $l) => new TemplateFormatter($l)
);

$stderr = Builder::chain(
    ResourceLogger::outputToStdErr(),
    fn (LoggerInterface $l) => new TemplateFormatter($l)
);

$logger = new LoggerCollection();
$logger->add(new MaxLogLevelFilter($stdout, LogLevel::WARNING));
$logger->add(new MinLogLevelFilter($stderr, LogLevel::ERROR));

$logger->info('Hello world');
```
