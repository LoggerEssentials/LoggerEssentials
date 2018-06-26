<?php
namespace Logger\Common;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

abstract class AbstractLoggerAware extends AbstractLogger implements LoggerInterface {
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
