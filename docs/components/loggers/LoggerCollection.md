# LoggerCollection

With a `LoggerCollection` you can consolidate several loggers to a single logger. In addition to that, you may use `LogLevelRangeFilter` to filter certain log-levels from the input.  

```PHP
$errorLogger = new LoggerCollection();
$errorLogger->add(new ResourceLogger(STDERR));
$errorLogger->add(new ErrorLogLogger());
$errorLogger->add(new PushoverLogger(/* ... */));
$logger = new LoggerCollection();
$logger->add(new LogLevelRangeFilter($errorLogger, LogLevel::ERROR, LogLevel::EMERGENCY));
$errorLogger->add(new LogLevelRangeFilter(new ResourceLogger(STDOUT), LogLevel::INFO, LogLevel::WARNING));
$logger->error("This is a log message");
```
