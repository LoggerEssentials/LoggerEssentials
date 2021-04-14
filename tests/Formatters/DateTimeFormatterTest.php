<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class DateTimeFormatterTest extends FormatterTestCase {
	public function test(): void {
		$testLogger = $this->createTestLogger();
		$logger = new DateTimeFormatter($testLogger);
		$logger->log(LogLevel::DEBUG, 'This is a test');
		self::assertRegExp('/\\[\\d+\\-\\d+\\-\\d+\\ \\d+:\\d+:\\d+\\] This is a test/', (string) $testLogger->getLastLine()->getMessage());
	}
}
