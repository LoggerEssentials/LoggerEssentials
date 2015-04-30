# LogLevelCompressor

Set an min and/or max log-level. If a log-message has a log-level that is not in the range of min- and max-log-level, then the level is changed to match either the min or the max log-level.

```php
$compressor = new LogLevelCompressor(new TemplateFormatter(new ResourceLogger(STDOUT)), LogLevel::INFO, LogLevel::CRITICAL);
$compressor->debug('Message');
```

```
[2015-01-01T00:00:00+00:00] INFO       Message - {}
```
