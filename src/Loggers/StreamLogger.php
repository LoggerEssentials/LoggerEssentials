<?php
namespace Logger\Loggers;

use RuntimeException;

class StreamLogger extends ResourceLogger {
	/**
	 * @param string $connectionUri
	 * @param string $mode
	 */
	public function __construct($connectionUri, $mode = 'ab+') {
		$resource = fopen($connectionUri, $mode);
		if($resource === false) {
			throw new RuntimeException('Could not open ressource');
		}
		parent::__construct($resource);
	}
}
