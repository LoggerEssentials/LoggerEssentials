# ExtendedLogger

In addition to the standard-interface LoggerInterface, ExtendedLogger provides a way to build a breadcrumb-path to better communicate the context of a log-entry.

Here is some code that illustrates, how this is meant:

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