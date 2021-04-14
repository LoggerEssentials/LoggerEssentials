<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class ReplaceFormatterTest extends FormatterTestCase {
	public function test(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new ReplaceFormatter($testLogger, array('h' => 'b'));
		$formatter->log(LogLevel::DEBUG, "hand");
		self::assertEquals('band', $testLogger->getLastLine()->getMessage());
	}
}
