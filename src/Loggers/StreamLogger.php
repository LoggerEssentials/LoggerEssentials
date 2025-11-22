<?php
namespace Logger\Loggers;

use RuntimeException;

class StreamLogger extends ResourceLogger {
	/**
	 * @param string $connectionUri
	 * @param string $mode
	 */
	public static function wrap(string $connectionUri, string $mode = 'ab+'): self {
		return new self($connectionUri, $mode);
	}

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
