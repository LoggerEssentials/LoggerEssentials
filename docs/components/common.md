# ExtendedLogger

You can create subloggers from a logger-instance. The reason is to easly create a base-context for all deriving log-messages. So you can track, how a certain log-message come from. In a different project, the call-context could be different.

```PHP
$psrLogger = ...;
$logger = new ExtendedPsrLoggerWrapper($psrLogger);
$logger = $logger->createSubLogger('Sub-Routine');
$logger = $logger->createSubLogger('Sub-Sub-Routine');
$logger->notice('Hello World'); // Sub-Routine / Sub-Sub-Routine: Hello World
```


# ExtendedPsrLoggerWrapper

In addition to the standard-interface LoggerInterface, ExtendedLogger provides a way to build a breadcrumb-path to better communicate the context of a log-entry.

Here is some code that illustrates, how this is meant:

## Sub-loggers

```php
<?php
use Logger\Common\ExtendedPsrLoggerWrapper;
use Logger\Formatters\TemplateFormatter;
use Logger\Loggers\ResourceLogger;

include 'vendor/autoload.php';

$logger = new TemplateFormatter(new ResourceLogger(STDOUT));
$logger = new ExtendedPsrLoggerWrapper($logger);

$orderIds = array(1234567, 7654321, 4352617);
$logger = $logger->createSubLogger('Process order');
$logger->info('Start');

foreach($orderIds as $orderId) {
	$childLogger = $logger->createSubLogger($orderId);
	$childLogger->info('Start processing');

	try {
		#processOrder($orderId);
		$childLogger->info('Successfully processed order');
	} catch (Exception $e) {
		$childLogger->critical($e->getMessage(), array('exception' => $e));
	}

}

$logger->info('Done');
```

Output:

```
[2015-04-01T00:00:00+00:00] INFO       Process order > 1234567: Start processing - {}
[2015-04-01T00:00:00+00:00] INFO       Process order > 1234567: Successfully processed order - {}
[2015-04-01T00:00:00+00:00] INFO       Process order > 7654321: Start processing - {}
[2015-04-01T00:00:00+00:00] INFO       Process order > 7654321: Successfully processed order - {}
[2015-04-01T00:00:00+00:00] INFO       Process order > 4352617: Start processing - {}
[2015-04-01T00:00:00+00:00] INFO       Process order > 4352617: Successfully processed order - {}
[2015-04-01T00:00:00+00:00] INFO       Process order: Done - {}
```

## Contexts

```php
<?php
use Logger\Common\ExtendedPsrLoggerWrapper;
use Logger\Formatters\TemplateFormatter;
use Logger\Loggers\ResourceLogger;

include 'vendor/autoload.php';

$logger = new TemplateFormatter(new ResourceLogger(STDOUT));
$logger = new ExtendedPsrLoggerWrapper($logger);

$logger->context(['a', 'b'], [], function () use ($logger) {
    $logger->context('c', [], function () use ($logger) {
        $logger->info('Test');
    });
});
```

Output:

```
[2015-04-01T00:00:00+00:00] INFO       a > b > c: Test - {}
```

## Intercepting

```php
<?php
use Logger\Common\ExtendedPsrLoggerWrapper;
use Logger\Formatters\TemplateFormatter;
use Logger\Loggers\ResourceLogger;

include 'vendor/autoload.php';

$logger = new TemplateFormatter(new ResourceLogger(STDOUT));
$logger = new ExtendedPsrLoggerWrapper($logger);

$logger->intercept(function () use ($logger) {
    $logger->info('Hello World');
}, function (CapturedLogEvent $logEvent) {
    $logEvent->getParentLogger()->log($logEvent->getLevel(), strtoupper($logEvent->getMessage()), $logEvent->getContext());
});
```

Output:

```
[2015-04-01T00:00:00+00:00] INFO       HELLO WORLD - {}
```
