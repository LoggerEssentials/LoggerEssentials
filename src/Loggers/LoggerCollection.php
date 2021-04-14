<?php
namespace Logger\Loggers;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class LoggerCollection extends AbstractLogger {
	/** @var LoggerInterface[] */
	private $loggers = [];

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
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []): void {
		foreach($this->loggers as $logger) {
			$logger->log($level, $message, $context);
		}
	}
}
