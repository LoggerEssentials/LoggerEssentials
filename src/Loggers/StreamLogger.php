<?php
namespace Logger\Loggers;

use Logger\Logger;

class StreamLogger extends ResourceLogger implements Logger {
	/**
	 * @param string $connectionUri
	 * @param string $mode
	 */
	public function __construct($connectionUri, $mode = 'a+') {
		$resource = fopen($connectionUri, $mode);
		parent::__construct($resource);
	}
}
