<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class MaxLengthFormatterTest extends FormatterTestCase {
	public function testPlain(): void {
		$testLogger = $this->createTestLogger();
		$logger = new MaxLengthFormatter($testLogger, 7, '');
		$logger->log(LogLevel::DEBUG, 'This is a test');
		self::assertEquals('This is', $testLogger->getLastLine()->getMessage());
	}

	public function testWithEllipsis(): void {
		$testLogger = $this->createTestLogger();
		$logger = new MaxLengthFormatter($testLogger, 11, ' ...');
		$logger->log(LogLevel::DEBUG, 'This is a test');
		self::assertEquals('This is ...', $testLogger->getLastLine()->getMessage());
	}

	public function testNotTooLong(): void {
		$testLogger = $this->createTestLogger();
		$logger = new MaxLengthFormatter($testLogger, 64, ' ...');
		$logger->log(LogLevel::DEBUG, 'This is a test');
		self::assertEquals('This is a test', $testLogger->getLastLine()->getMessage());
	}
}
