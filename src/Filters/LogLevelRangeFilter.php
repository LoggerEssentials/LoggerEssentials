<?php
namespace Logger\Filters;

use Logger\Common\AbstractLoggerAware;
use Logger\Tools\LogLevelTranslator;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LogLevelRangeFilter extends AbstractLoggerAware {
	/** @var int */
	private $minLevel;
	/** @var int */
	private $maxLevel;

	/**
	 * @param LoggerInterface $logger
	 * @param string $minLevel
	 * @param string $maxLevel
	 */
	public function __construct(LoggerInterface $logger, $minLevel = LogLevel::DEBUG, $maxLevel = LogLevel::EMERGENCY) {
		parent::__construct($logger);
		$this->minLevel = 7 - LogLevelTranslator::getLevelNo($minLevel);
		$this->maxLevel = 7 - LogLevelTranslator::getLevelNo($maxLevel);
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($psrLevel, $message, array $context = array()) {
		$level = 7 - LogLevelTranslator::getLevelNo($psrLevel);
		if($this->minLevel <= $level && $this->maxLevel >= $level) {
			$this->logger()->log($psrLevel, $message, $context);
		}
	}
}
