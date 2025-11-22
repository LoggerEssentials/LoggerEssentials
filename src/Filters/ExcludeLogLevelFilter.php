<?php
namespace Logger\Filters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class ExcludeLogLevelFilter extends AbstractLoggerAware {
	/** @var string */
	private $excludedLogLevel;

	/**
	 * @param LoggerInterface $logger
	 * @param string $excludedLogLevel
	 */
	public function __construct(LoggerInterface $logger, $excludedLogLevel) {
		parent::__construct($logger);
		$this->excludedLogLevel = $excludedLogLevel;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		if($this->excludedLogLevel !== $level) {
			$this->logger()->log($level, $message, $context);
		}
	}
}
