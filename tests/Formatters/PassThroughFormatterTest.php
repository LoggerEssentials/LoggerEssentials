<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class PassThroughFormatterTest extends FormatterTestCase {
	public function test() {
		$testLogger = $this->createTestLogger();
		$logger = new PassThroughFormatter($testLogger);
		$logger->log(LogLevel::DEBUG, 'test');
		$this->assertEquals('test', $testLogger->getLastLine()->getMessage());
	}
}
