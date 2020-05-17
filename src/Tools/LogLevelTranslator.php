<?php
namespace Logger\Tools;

use Logger\Exceptions\LogLevelNotFoundException;
use Psr\Log\LogLevel;

final class LogLevelTranslator {
	/** @var array */
	private static $levelsA = [
		Rfc5424LogLevels::EMERGENCY => LogLevel::EMERGENCY,
		Rfc5424LogLevels::ALERT => LogLevel::ALERT,
		Rfc5424LogLevels::CRITICAL => LogLevel::CRITICAL,
		Rfc5424LogLevels::ERROR => LogLevel::ERROR,
		Rfc5424LogLevels::WARNING => LogLevel::WARNING,
		Rfc5424LogLevels::NOTICE => LogLevel::NOTICE,
		Rfc5424LogLevels::INFO => LogLevel::INFO,
		Rfc5424LogLevels::DEBUG => LogLevel::DEBUG
	];
	/** @var array */
	private static $levelsB = [
		LogLevel::EMERGENCY => Rfc5424LogLevels::EMERGENCY,
		LogLevel::ALERT => Rfc5424LogLevels::ALERT,
		LogLevel::CRITICAL => Rfc5424LogLevels::CRITICAL,
		LogLevel::ERROR => Rfc5424LogLevels::ERROR,
		LogLevel::WARNING => Rfc5424LogLevels::WARNING,
		LogLevel::NOTICE => Rfc5424LogLevels::NOTICE,
		LogLevel::INFO => Rfc5424LogLevels::INFO,
		LogLevel::DEBUG => Rfc5424LogLevels::DEBUG
	];

	/**
	 * @return array
	 */
	public static function getRfc5424Levels(): array {
		return self::$levelsB;
	}

	/**
	 * @return array
	 */
	public static function getLevelTokens(): array {
		return self::$levelsA;
	}

	/**
	 * @param string $levelToken
	 * @throws LogLevelNotFoundException
	 * @return int
	 */
	public static function getLevelNo($levelToken): int {
		return (int) self::getFrom(self::$levelsB, $levelToken);
	}

	/**
	 * @param int $levelNo
	 * @throws LogLevelNotFoundException
	 * @return string
	 */
	public static function getLevelToken($levelNo): string {
		return (string) self::getFrom(self::$levelsA, $levelNo);
	}

	/**
	 * @param array $levels
	 * @param string|int $level
	 * @return string|int
	 * @throws LogLevelNotFoundException
	 */
	private static function getFrom(array $levels, $level) {
		if(array_key_exists($level, $levels)) {
			return $levels[$level];
		}
		throw new LogLevelNotFoundException("Log-level not found: {$level}");
	}
}
