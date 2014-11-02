<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class MessagePrefixFormatterTest extends FormatterTestCase {
	public function testPlain() {
		$testLogger = $this->createTestLogger();
		$logger = new MessagePrefixFormatter($testLogger, 'Prefix');
		$logger->log(LogLevel::DEBUG, 'This is a test');
		$this->assertEquals('Prefix: This is a test', $testLogger->lastLine());
	}

	public function testArray() {
		$testLogger = $this->createTestLogger();
		$logger = new MessagePrefixFormatter($testLogger, array('Level0', 'Level1', 'Level2'));
		$logger->log(LogLevel::DEBUG, 'This is a test');
		$this->assertEquals('Level0 > Level1 > Level2: This is a test', $testLogger->lastLine());
	}
}
