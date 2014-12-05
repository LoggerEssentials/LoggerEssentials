<?php
namespace Logger\Filters;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class MaxLogLevelFilter extends LogLevelRangeFilter {
	/**
	 * @param LoggerInterface $logger
	 * @param string $maxLevel
	 */
	public function __construct(LoggerInterface $logger, $maxLevel = LogLevel::EMERGENCY) {
		parent::__construct($logger, LogLevel::DEBUG, $maxLevel);
	}
}