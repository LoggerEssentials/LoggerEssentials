# LoggerEssentials

## Example

```PHP
$logger = new LoggerCollection();
$logger->add(new MaxLogLevelFilter(new TemplateFormatter(new ResourceLogger(STDOUT)), LogLevel::WARNING));
$logger->add(new MinLogLevelFilter(new TemplateFormatter(new ResourceLogger(STDERR)), LogLevel::ERROR));

$logger->info('Hello world');
```
