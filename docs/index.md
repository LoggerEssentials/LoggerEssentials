# LoggerEssentials

## What is LoggerEssentials?

[LoggerEssentials](https://github.com/LoggerEssentials/LoggerEssentials) is a collection of PSR-3 compatible loggers and wrappers to get a greater control of your logs. 

## Example

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

## Combine with monolog

You could use Monolog as an output-channel, which is also PSR-3 compatible but ships propritary log-handlers. LoggerEssentials assumes, that all LogHandlers are PSR-3 compatible, so you can't use monolog's handlers directly. May be I could create a wrapper for them at some time. 

```php
use Logger\Builder;
use Logger\Loggers\LoggerCollection;
use Logger\Formatters\TemplateFormatter;
use Logger\Filters\MaxLogLevelFilter;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

$slackLogger = (new Monolog\Logger( /* ... */ ))->pushHandler(new SlackHandler( /* ... */ )); // *1

$formattedSlack = Builder::chain(
    $slackLogger,
    fn (LoggerInterface $l) => new TemplateFormatter($l)
);

$logger = new LoggerCollection();
$logger->add(new MaxLogLevelFilter($formattedSlack, LogLevel::DEBUG));

$logger->info('Hello world');
```
_*1: Works since [72123e3](https://github.com/Seldaek/monolog/commit/72123e3d6c7bf8f1454fe12deb69db2f783dd220)_
