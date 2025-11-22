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
class MinLogLevelFilter extends AbstractLoggerAware {
	private int $minLevel;

	public static function wrap(LoggerInterface $logger, string $minLevel = LogLevel::DEBUG): self {
		return new self($logger, $minLevel);
	}

	/**
	 * @param LoggerInterface $logger
	 * @param string $minLevel
	 */
	public function __construct(LoggerInterface $logger, $minLevel = LogLevel::DEBUG) {
		parent::__construct($logger);
		$this->minLevel = 7 - LogLevelTranslator::getLevelNo($minLevel);
	}

	/**
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$level = 7 - LogLevelTranslator::getLevelNo($level);
		if($this->minLevel <= $level) {
			$this->logger()->log($level, $message, $context);
		}
	}
}
