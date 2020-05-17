<?php
namespace Logger\Manipulators;

use Logger\Tools\LogLevelTranslator;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LogLevelCompressor extends AbstractLogger {
	/** @var LoggerInterface */
	private $logger;
	/** @var int */
	private $minLevel;
	/** @var int */
	private $maxLevel;

	/**
	 * LogLevelCompressor constructor.
	 * @param LoggerInterface $logger
	 * @param string $minLevel
	 * @param string $maxLevel
	 */
	public function __construct(LoggerInterface $logger, $minLevel = LogLevel::DEBUG, $maxLevel = LogLevel::EMERGENCY) {
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
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []) {
		$level = $this->compress($level);
		$this->logger->log($level, $message, $context);
	}
}
