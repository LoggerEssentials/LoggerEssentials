<?php
namespace Logger\Loggers;

use Psr\Log\AbstractLogger;

class ResourceLogger extends AbstractLogger {
	/** @var resource */
	private $resource;

	/**
	 * @param resource $resource
	 */
	public function __construct($resource) {
		$this->resource = $resource;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = array()) {
		fwrite($this->resource, $message);
	}
}
