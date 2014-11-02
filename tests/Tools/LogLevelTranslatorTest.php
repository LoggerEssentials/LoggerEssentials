<?php
namespace Kir\Logging\Essentials\Tools;

use Logger\Tools\LogLevelTranslator;
use Logger\Tools\Rfc5424LogLevels;
use Psr\Log\LogLevel;

class LogLevelTranslatorTest extends \PHPUnit_Framework_TestCase {
	public function testLevelNumbers1() {
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

	public function testLevelNumbers2() {
		$levels = LogLevelTranslator::getRfc5424Levels();
		$this->_testLevelNumbers($levels);
	}

	public function testLevelTokens1() {
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

	public function testLevelTokenss() {
		$tokens = LogLevelTranslator::getLevelTokens();
		$this->_testLevelTokens($tokens);
	}

	private function _testLevelNumbers(array $levels) {
		foreach($levels as $levelA => $levelB) {
			$level = LogLevelTranslator::getLevelToken($levelB);
			$this->assertEquals($levelA, $level);
		}
	}

	private function _testLevelTokens(array $tokens) {
		foreach($tokens as $levelA => $levelB) {
			$level = LogLevelTranslator::getLevelNo($levelB);
			$this->assertEquals($levelA, $level);
		}
	}
}
 