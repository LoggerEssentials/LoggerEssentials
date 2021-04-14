<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class CallbackFormatterTest extends FormatterTestCase {
	public function test(): void {
		$testLogger = $this->createTestLogger();
		$logger = new CallbackFormatter($testLogger, function (string $level, string $message, array $context) {
			return (string) json_encode(['lvl' => $level, 'msg' => $message]);
		});
		$logger->log(LogLevel::DEBUG, 'This is a test');
		self::assertEquals('{"lvl":"debug","msg":"This is a test"}', $testLogger->getLastLine()->getMessage());
	}
}
