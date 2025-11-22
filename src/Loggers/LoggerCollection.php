<?php
namespace Logger\Loggers;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Stringable;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class LoggerCollection extends AbstractLogger {
	/** @var LoggerInterface[] */
	private $loggers = [];

	/**
	 * @param array<int, LoggerInterface> $loggers
	 */
	public static function wrap(array $loggers = []): self {
		return new self($loggers);
	}

	/**
	 * @param array<int, LoggerInterface> $loggers
	 */
	public function __construct(array $loggers = []) {
		foreach($loggers as $logger) {
			$this->add($logger);
		}
	}

	/**
	 * @param LoggerInterface $logger
	 * @return $this
	 */
	public function add(LoggerInterface $logger): self {
		$this->loggers[] = $logger;
		return $this;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		foreach($this->loggers as $logger) {
			$logger->log($level, $message, $context);
		}
	}
}
