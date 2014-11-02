<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class NobrFormatterTest extends FormatterTestCase {
	public function test() {
		$testLogger = $this->createTestLogger();
		$logger = new NobrFormatter($testLogger);
		$logger->log(LogLevel::DEBUG, "This\nis\ra\r\ntest");
		$this->assertEquals('This is a test', $testLogger->getLastLine());
	}
}
