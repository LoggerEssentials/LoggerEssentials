# ContextExtender

Can be used to extend a `context` by using an (key-value-)array.

```PHP
$logger = new ResourceLogger(STDOUT);
$logger = new ContextExtender($logger, array('test2' => 456));
$logger->info('Hello world', array('test1' => 123));
// Context is not ['test1' => 123, 'test2' => 456]
```
