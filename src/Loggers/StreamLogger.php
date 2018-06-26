<?php
namespace Logger\Loggers;

use Psr\Log\LoggerInterface;

class StreamLogger extends ResourceLogger implements LoggerInterface {
	/**
	 * @param string $connectionUri
	 * @param string $mode
	 */
	public function __construct($connectionUri, $mode = 'a+') {
		$resource = fopen($connectionUri, $mode);
		parent::__construct($resource);
	}
}
