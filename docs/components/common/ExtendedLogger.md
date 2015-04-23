# ExtendedLogger

You can create subloggers from a logger-instance. The reason is to easly create a base-context for all deriving log-messages. So you can track, how a certain log-message come from. In a different project, the call-context could be different.

```PHP
$psrLogger = ...;
$logger = new ExtendedPsrLoggerWrapper($psrLogger);
$logger = $logger->createSubLogger('Sub-Routine');
$logger = $logger->createSubLogger('Sub-Sub-Routine');
$logger->notice('Hello World'); // Sub-Routine / Sub-Sub-Routine: Hello World
```
