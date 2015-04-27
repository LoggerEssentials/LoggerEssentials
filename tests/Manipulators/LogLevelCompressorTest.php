<?php
namespace Logger\Manipulators;

use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

class LogLevelCompressorTest extends \PHPUnit_Framework_TestCase {
	public function testMin() {
		$compressor = new LogLevelCompressor(new NullLogger(), LogLevel::INFO, LogLevel::CRITICAL);
		$level = $compressor->compress(LogLevel::EMERGENCY);
		$this->assertEquals($level, LogLevel::CRITICAL);
	}

	public function testMax() {
		$compressor = new LogLevelCompressor(new NullLogger(), LogLevel::INFO, LogLevel::CRITICAL);
		$level = $compressor->compress(LogLevel::DEBUG);
		$this->assertEquals($level, LogLevel::INFO);
	}

	public function testEqual() {
		$compressor = new LogLevelCompressor(new NullLogger(), LogLevel::INFO, LogLevel::CRITICAL);
		$level = $compressor->compress(LogLevel::WARNING);
		$this->assertEquals($level, LogLevel::WARNING);
	}
}
