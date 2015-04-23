# Formatters

## CallbackFormatter


## ContextJsonFormatter


## DateTimeFormatter


## FormatFormatter


## MaxLengthFormatter


## MessagePrefixFormatter

Add a prefix to all log messages:

```PHP
$logger = new MessagePrefixProxy(new ResourceLogger(STDOUT), 'AddCustomer: ');
```


## NobrFormatter


## PassThroughFormatter


## ReplaceFormatter


## TemplateFormatter


## TrimFormatter