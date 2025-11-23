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
class MaxLogLevelFilter extends AbstractLoggerAware {
	private int $maxLevel;

	public static function wrap(LoggerInterface $logger, string $maxLevel = LogLevel::EMERGENCY): self {
		return new self($logger, $maxLevel);
	}

	/**
	 * @param LoggerInterface $logger
	 * @param string $maxLevel
	 */
	public function __construct(LoggerInterface $logger, $maxLevel = LogLevel::EMERGENCY) {
		parent::__construct($logger);
		$this->maxLevel = 7 - LogLevelTranslator::getLevelNo($maxLevel);
	}

	/**
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$curLevel = 7 - LogLevelTranslator::getLevelNo($level);
		if($this->maxLevel >= $curLevel) {
			$this->logger()->log($level, $message, $context);
		}
	}
}
