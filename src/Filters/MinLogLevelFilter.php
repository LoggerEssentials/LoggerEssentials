<?php
namespace Logger\Filters;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class MinLogLevelFilter extends LogLevelRangeFilter {
	/**
	 * @param LoggerInterface $logger
	 * @param string $minLevel
	 */
	public function __construct(LoggerInterface $logger, $minLevel = LogLevel::EMERGENCY) {
		parent::__construct($logger, $minLevel);
	}
}
