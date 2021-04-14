<?php
namespace Logger\Manipulators;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

class LogLevelCompressorTest extends TestCase {
	public function testMin(): void {
		$compressor = new LogLevelCompressor(new NullLogger(), LogLevel::INFO, LogLevel::CRITICAL);
		$level = $compressor->compress(LogLevel::EMERGENCY);
		self::assertEquals(LogLevel::CRITICAL, $level);
	}

	public function testMax(): void {
		$compressor = new LogLevelCompressor(new NullLogger(), LogLevel::INFO, LogLevel::CRITICAL);
		$level = $compressor->compress(LogLevel::DEBUG);
		self::assertEquals(LogLevel::INFO, $level);
	}

	public function testEqual(): void {
		$compressor = new LogLevelCompressor(new NullLogger(), LogLevel::INFO, LogLevel::CRITICAL);
		$level = $compressor->compress(LogLevel::WARNING);
		self::assertEquals(LogLevel::WARNING, $level);
	}
}
