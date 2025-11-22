<?php
namespace Logger\Loggers;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\AbstractLogger;
use Throwable;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class ErrorLogLogger extends AbstractLogger {
	/** @var int&(0|1|3|4) */
	private int $messageType;
	private ?string $destination;
	private ?string $extraHeaders;

	/**
	 * @param int&(0|1|3|4) $messageType
	 * @param null|string $destination
	 * @param null|string $extraHeaders
	 */
	public function __construct($messageType = null, ?string $destination = null, ?string $extraHeaders = null) {
		$this->messageType = (int) $messageType;
		$this->destination = $destination;
		$this->extraHeaders = $extraHeaders;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		try {
			if($this->messageType === null) { // @phpstan-ignore-line
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
