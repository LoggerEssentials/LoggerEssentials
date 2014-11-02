<?php
namespace Logger\Formatters;

use Logger\Common\TestLogger;
use Psr\Log\LogLevel;

class TrimFormatterTest extends \PHPUnit_Framework_TestCase {
	public function test() {
		$testLogger = new TestLogger();
		$formatter = new TrimFormatter($testLogger);
		$formatter->log(LogLevel::DEBUG, "    test    ");
		$this->assertEquals('test', $testLogger->getLastLine());
	}
}
