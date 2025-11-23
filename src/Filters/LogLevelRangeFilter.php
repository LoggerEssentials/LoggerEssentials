<?php
namespace Logger\Filters;

use Logger\Common\AbstractLoggerAware;
use Logger\Tools\LogLevelTranslator;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class LogLevelRangeFilter extends AbstractLoggerAware {
	private int $minLevel;
	private int $maxLevel;

	public static function wrap(LoggerInterface $logger, string $minLevel = LogLevel::DEBUG, string $maxLevel = LogLevel::EMERGENCY): self {
		return new self($logger, $minLevel, $maxLevel);
	}

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
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$curLevel = 7 - LogLevelTranslator::getLevelNo($level);
		if($this->minLevel <= $curLevel && $this->maxLevel >= $curLevel) {
			$this->logger()->log($level, $message, $context);
		}
	}
}
