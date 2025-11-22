<?php
namespace Logger\Common;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Stringable;

/**
 * @phpstan-type TLogLevel string
 * @phpstan-type TLogMessage string|Stringable
 * @phpstan-type TLogContext array<string, mixed>
 */
abstract class AbstractLoggerAware extends AbstractLogger {
	/** @var LoggerInterface */
	private $logger;

	/**
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger) {
		$this->logger = $logger;
	}

	/**
	 * @return LoggerInterface
	 */
	protected function logger(): LoggerInterface {
		return $this->logger;
	}
}
