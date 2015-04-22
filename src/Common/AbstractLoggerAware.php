<?php
namespace Logger\Common;

use Logger\Logger;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

abstract class AbstractLoggerAware extends AbstractLogger implements Logger {
	/** @var LoggerInterface */
	private $logger = null;

	/**
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger) {
		$this->logger = $logger;
	}

	/**
	 * @return LoggerInterface
	 */
	protected function logger() {
		return $this->logger;
	}
}