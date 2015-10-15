<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class DateTimeFormatterTest extends FormatterTestCase {
	public function test() {
		$testLogger = $this->createTestLogger();
		$logger = new DateTimeFormatter($testLogger);
		$logger->log(LogLevel::DEBUG, 'This is a test');
		$this->assertRegExp('/\\[\\d+\\-\\d+\\-\\d+\\ \\d+:\\d+:\\d+\\] This is a test/', $testLogger->getLastLine()->getMessage());
	}
}
