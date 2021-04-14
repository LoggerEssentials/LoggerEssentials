<?php
namespace Logger\Tools;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class LogLevelTranslatorTest extends TestCase {
	/**
	 */
	public function testLevelNumbers1(): void {
		$levels = array(
			LogLevel::EMERGENCY => Rfc5424LogLevels::EMERGENCY,
			LogLevel::ALERT => Rfc5424LogLevels::ALERT,
			LogLevel::CRITICAL => Rfc5424LogLevels::CRITICAL,
			LogLevel::ERROR => Rfc5424LogLevels::ERROR,
			LogLevel::WARNING => Rfc5424LogLevels::WARNING,
			LogLevel::NOTICE => Rfc5424LogLevels::NOTICE,
			LogLevel::INFO => Rfc5424LogLevels::INFO,
			LogLevel::DEBUG => Rfc5424LogLevels::DEBUG
		);
		$this->_testLevelNumbers($levels);
	}

	/**
	 */
	public function testLevelNumbers2(): void {
		$levels = LogLevelTranslator::getRfc5424Levels();
		$this->_testLevelNumbers($levels);
	}

	/**
	 */
	public function testLevelTokens1(): void {
		$tokens = array(
			Rfc5424LogLevels::EMERGENCY => LogLevel::EMERGENCY,
			Rfc5424LogLevels::ALERT => LogLevel::ALERT,
			Rfc5424LogLevels::CRITICAL => LogLevel::CRITICAL,
			Rfc5424LogLevels::ERROR => LogLevel::ERROR,
			Rfc5424LogLevels::WARNING => LogLevel::WARNING,
			Rfc5424LogLevels::NOTICE => LogLevel::NOTICE,
			Rfc5424LogLevels::INFO => LogLevel::INFO,
			Rfc5424LogLevels::DEBUG => LogLevel::DEBUG
		);
		$this->_testLevelTokens($tokens);
	}

	/**
	 */
	public function testLevelTokens(): void {
		$tokens = LogLevelTranslator::getLevelTokens();
		$this->_testLevelTokens($tokens);
	}

	/**
	 * @param array<string, int> $levels
	 */
	private function _testLevelNumbers(array $levels): void {
		foreach($levels as $levelA => $levelB) {
			$level = LogLevelTranslator::getLevelToken($levelB);
			self::assertEquals($levelA, $level);
		}
	}

	/**
	 * @param array<int, string> $tokens
	 */
	private function _testLevelTokens(array $tokens): void {
		foreach($tokens as $levelA => $levelB) {
			$level = LogLevelTranslator::getLevelNo($levelB);
			self::assertEquals($levelA, $level);
		}
	}
}
