<?php
namespace Logger\Common;

use Logger\Logger;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class LoggerCollection extends AbstractLogger implements Logger {
	/**
	 * @var LoggerInterface[]
	 */
	private $loggers = array();

	/**
	 * @param array $loggers
	 */
	public function __construct(array $loggers = array()) {
		foreach($loggers as $logger) {
			$this->add($logger);
		}
	}

	/**
	 * @param LoggerInterface $logger
	 * @return $this
	 */
	public function add(LoggerInterface $logger) {
		$this->loggers[] = $logger;
		return $this;
	}

	/**
	 * Logs with an arbitrary level.
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		foreach($this->loggers as $logger) {
			$logger->log($level, $message, $context);
		}
	}
}