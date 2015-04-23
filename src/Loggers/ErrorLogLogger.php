<?php
namespace Logger\Loggers;

use Logger\Logger;
use Psr\Log\AbstractLogger;

class ErrorLogLogger extends AbstractLogger implements Logger {
	/** @var mixed */
	private $messageType;
	/** @var mixed */
	private $destination;
	/** @var mixed */
	private $extraHeaders;

	/**
	 * @param int $messageType
	 * @param mixed $destination
	 * @param mixed $extraHeaders
	 */
	public function __construct($messageType = null, $destination = null, $extraHeaders = null) {
		$this->messageType = $messageType;
		$this->destination = $destination;
		$this->extraHeaders = $extraHeaders;
	}

	/**
	 * Logs with an arbitrary level.
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		try {
			if($this->messageType === null) {
				error_log($message);
			} elseif((int)$this->messageType === 0 || (int)$this->messageType === 4) {
				error_log($message, $this->messageType);
			} else {
				error_log($message, (int)$this->messageType, $this->destination, $this->extraHeaders);
			}
		} catch(\Exception $e) {
		}
	}
}