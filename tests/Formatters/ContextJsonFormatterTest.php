<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class ContextJsonFormatterTest extends FormatterTestCase {
	public function test(): void {
		$testLogger = $this->createTestLogger();
		$logger = new ContextJsonFormatter($testLogger);
		$logger->log(LogLevel::DEBUG, 'This is a test');
		self::assertEquals('This is a test {}', $testLogger->getLastLine()->getMessage());
	}
}
