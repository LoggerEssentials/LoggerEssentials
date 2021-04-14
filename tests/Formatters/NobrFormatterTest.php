<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class NobrFormatterTest extends FormatterTestCase {
	public function test(): void {
		$testLogger = $this->createTestLogger();
		$logger = new NobrFormatter($testLogger);
		$logger->log(LogLevel::DEBUG, "This\nis\ra\r\ntest");
		self::assertEquals('This is a test', $testLogger->getLastLine()->getMessage());
	}
}
