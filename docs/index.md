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
