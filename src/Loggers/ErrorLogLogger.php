<?php
namespace Logger\Loggers;

use Psr\Log\AbstractLogger;
use Throwable;

class ErrorLogLogger extends AbstractLogger {
	/** @var int&(0|1|3|4) */
	private $messageType;
	/** @var mixed */
	private $destination;
	/** @var mixed */
	private $extraHeaders;

	/**
	 * @param int&(0|1|3|4) $messageType
	 * @param mixed $destination
	 * @param mixed $extraHeaders
	 */
	public function __construct($messageType = null, $destination = null, $extraHeaders = null) {
		$this->messageType = (int) $messageType;
		$this->destination = $destination;
		$this->extraHeaders = $extraHeaders;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []): void {
		try {
			if($this->messageType === null) {
				/** @noinspection ForgottenDebugOutputInspection */
				error_log($message);
			} elseif((int) $this->messageType === 0 || (int) $this->messageType === 4) {
				/** @noinspection ForgottenDebugOutputInspection */
				error_log($message, $this->messageType);
			} else {
				/** @noinspection ForgottenDebugOutputInspection */
				error_log($message, (int) $this->messageType, $this->destination, $this->extraHeaders);
			}
		} catch(Throwable $e) {
		}
	}
}
