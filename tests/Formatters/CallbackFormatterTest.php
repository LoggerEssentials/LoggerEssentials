<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class CallbackFormatterTest extends FormatterTestCase {
	public function test() {
		$testLogger = $this->createTestLogger();
		$logger = new CallbackFormatter($testLogger, function ($level, $message, $context) {
			return json_encode(array('lvl' => $level, 'msg' => $message));
		});
		$logger->log(LogLevel::DEBUG, 'This is a test');
		$this->assertEquals('{"lvl":"debug","msg":"This is a test"}', $testLogger->lastLine());
	}
}
