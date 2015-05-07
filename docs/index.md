# LoggerEssentials

## What is LoggerEssentials?

[LoggerEssentials](https://github.com/LoggerEssentials/LoggerEssentials) is a collection of PSR-3 compatible loggers and wrappers to get a greater control of your logs. 

## Example

```PHP
$logger = new LoggerCollection();
$logger->add(new MaxLogLevelFilter(new TemplateFormatter(new ResourceLogger(STDOUT)), LogLevel::WARNING));
$logger->add(new MinLogLevelFilter(new TemplateFormatter(new ResourceLogger(STDERR)), LogLevel::ERROR));

$logger->info('Hello world');
```

## Combine with monolog

You could use Monolog as an output-channel, which is also PSR-3 compatible but ships propritary log-handlers. LoggerEssentials assumes, that all LogHandlers are PSR-3 compatible, so you can't use those handlers directly. May be I could create a wrapper for them at some time. 

```PHP
// This is not working due to a missing self-reference return. *arg*
// $slackLogger = (new Monolog\Logger())->pushHandler(new SlackHandler( /* ... */ ));

$monolog = (new Monolog\Logger());
$monolog->pushHandler(new SlackHandler( /* ... */ ));
$slackLogger = $monolog;

$logger = new LoggerCollection();
$logger->add(new MaxLogLevelFilter(new TemplateFormatter($slackLogger), LogLevel::DEBUG));

$logger->info('Hello world');
```
