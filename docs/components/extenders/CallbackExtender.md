# CallbackExtender

Extends a `log-item` by using a callback-function.

```PHP
$logger = new ResourceLogger(STDOUT);
$logger = new CallbackExtender($logger, function ($level, &$message) {
	if($level === LogLevel::INFO) {
		$message = preg_replace('/\\bworld\\b/', 'planet', $message);
	}
});
$logger->info('Hello world');
```
