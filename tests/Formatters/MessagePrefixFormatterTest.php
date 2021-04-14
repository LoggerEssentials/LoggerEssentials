<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class MessagePrefixFormatterTest extends FormatterTestCase {
	public function testPlain(): void {
		$testLogger = $this->createTestLogger();
		$logger = new MessagePrefixFormatter($testLogger, 'Prefix');
		$logger->log(LogLevel::DEBUG, 'This is a test');
		self::assertEquals('Prefix: This is a test', $testLogger->getLastLine()->getMessage());
	}

	public function testArray(): void {
		$testLogger = $this->createTestLogger();
		$logger = new MessagePrefixFormatter($testLogger, array('Level0', 'Level1', 'Level2'));
		$logger->log(LogLevel::DEBUG, 'This is a test');
		self::assertEquals('Level0 > Level1 > Level2: This is a test', $testLogger->getLastLine()->getMessage());
	}
}
