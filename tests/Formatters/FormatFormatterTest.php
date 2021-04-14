<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class FormatFormatterTest extends FormatterTestCase {
	public function test(): void {
		$testLogger = $this->createTestLogger();
		$logger = new FormatFormatter($testLogger, "[%s]");
		$logger->log(LogLevel::DEBUG, 'This is a test');
		self::assertEquals('[This is a test]', $testLogger->getLastLine()->getMessage());
	}
}
