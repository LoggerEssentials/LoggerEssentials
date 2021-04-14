<?php
namespace Logger\Formatters;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class TrimFormatterTest extends TestCase {
	public function test(): void {
		$testLogger = new TestLogger();
		$formatter = new TrimFormatter($testLogger);
		$formatter->log(LogLevel::DEBUG, "    test    ");
		self::assertEquals('test', $testLogger->getLastLine()->getMessage());
	}
}
