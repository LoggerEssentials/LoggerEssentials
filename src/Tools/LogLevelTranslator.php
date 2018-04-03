<?php
namespace Logger\Tools;

use Logger\Exceptions\LogLevelNotFoundException;
use Psr\Log\LogLevel;

final class LogLevelTranslator {
	/** @var array */
	private static $levelsA = array(
		Rfc5424LogLevels::EMERGENCY => LogLevel::EMERGENCY,
		Rfc5424LogLevels::ALERT => LogLevel::ALERT,
		Rfc5424LogLevels::CRITICAL => LogLevel::CRITICAL,
		Rfc5424LogLevels::ERROR => LogLevel::ERROR,
		Rfc5424LogLevels::WARNING => LogLevel::WARNING,
		Rfc5424LogLevels::NOTICE => LogLevel::NOTICE,
		Rfc5424LogLevels::INFO => LogLevel::INFO,
		Rfc5424LogLevels::DEBUG => LogLevel::DEBUG
	);
	/** @var array */
	private static $levelsB = array(
		LogLevel::EMERGENCY => Rfc5424LogLevels::EMERGENCY,
		LogLevel::ALERT => Rfc5424LogLevels::ALERT,
		LogLevel::CRITICAL => Rfc5424LogLevels::CRITICAL,
		LogLevel::ERROR => Rfc5424LogLevels::ERROR,
		LogLevel::WARNING => Rfc5424LogLevels::WARNING,
		LogLevel::NOTICE => Rfc5424LogLevels::NOTICE,
		LogLevel::INFO => Rfc5424LogLevels::INFO,
		LogLevel::DEBUG => Rfc5424LogLevels::DEBUG
	);

	/**
	 * @return array
	 */
	static public function getRfc5424Levels() {
		return self::$levelsB;
	}

	/**
	 * @return array
	 */
	static public function getLevelTokens() {
		return self::$levelsA;
	}

	/**
	 * @param string $levelToken
	 * @throws LogLevelNotFoundException
	 * @return int
	 */
	static public function getLevelNo($levelToken) {
		return (int) self::getFrom(self::$levelsB, $levelToken);
	}

	/**
	 * @param int $levelNo
	 * @throws LogLevelNotFoundException
	 * @return string
	 */
	static public function getLevelToken($levelNo) {
		return (string) self::getFrom(self::$levelsA, $levelNo);
	}

	/**
	 * @param array $levels
	 * @param string|int $level
	 * @return string|int
	 * @throws LogLevelNotFoundException
	 */
	static private function getFrom(array $levels, $level) {
		if(array_key_exists($level, $levels)) {
			return $levels[$level];
		}
		throw new LogLevelNotFoundException("Log-level not found: {$level}");
	}
}
