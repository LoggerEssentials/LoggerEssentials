# Filters

## CallbackFilter


## ExcludeLogLevelFilter


# LogLevelRangeFilter

Define a range of valid log-levels.

```PHP
$logger = new LoggerCollection();
$logger->add(new SingleLogLevelFilterProxy(new StreamLogger(STDOUT), LogLevel::INFO, LogLevel::ERROR));
$logger->add(new SingleLogLevelFilterProxy(new StreamLogger(STDERR), LogLevel::ERROR, LogLevel::EMERGENCY));
$logger->notice('test');
```


## MaxLogLevelFilter


## MinLogLevelFilter


## RegularExpressionFilter

