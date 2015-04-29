<?php
namespace Logger\Filters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class ExcludeLogLevelFilter extends AbstractLoggerAware {
	/** @var string */
	private $excludedLogLevel = null;

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
	 * @param mixed $psrLevel
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($psrLevel, $message, array $context = array()) {
		if($this->excludedLogLevel != $psrLevel) {
			$this->logger()->log($psrLevel, $message, $context);
		}
	}
}
