<?php
namespace Logger\Manipulators;

use Logger\Common\AbstractLoggerAware;
use Logger\Tools\LogLevelTranslator;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class LogLevelCompressor extends AbstractLogger {
	private LoggerInterface $logger;
	private int $minLevel;
	private int $maxLevel;

	/**
	 * LogLevelCompressor constructor.
	 * @param LoggerInterface $logger
	 * @param string $minLevel
	 * @param string $maxLevel
	 */
	public function __construct(LoggerInterface $logger, string $minLevel = LogLevel::DEBUG, string $maxLevel = LogLevel::EMERGENCY) {
		$this->logger = $logger;
		$this->minLevel = LogLevelTranslator::getLevelNo($minLevel);
		$this->maxLevel = LogLevelTranslator::getLevelNo($maxLevel);
	}

	/**
	 * @param string $level
	 * @return string
	 */
	public function compress($level) {
		$levelNo = LogLevelTranslator::getLevelNo($level);
		$levelNo = min($this->minLevel, $levelNo);
		$levelNo = max($this->maxLevel, $levelNo);
		$level = LogLevelTranslator::getLevelToken($levelNo);
		return $level;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$level = $this->compress($level);
		$this->logger->log($level, $message, $context);
	}
}
