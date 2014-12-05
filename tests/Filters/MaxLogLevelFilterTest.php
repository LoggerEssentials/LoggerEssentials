<?php
namespace Logger\Filters;

use Logger\Common\TestLogger;
use Psr\Log\LogLevel;

class MaxLogLevelFilterTest extends \PHPUnit_Framework_TestCase {
	public function test() {
		$testLogger = new TestLogger();
		$logger = new MaxLogLevelFilter($testLogger, LogLevel::WARNING);

		$logger->error('test');
		$this->assertNotEquals('test', $testLogger->getLastLine());

		$logger->warning('test');
		$this->assertEquals('test', $testLogger->getLastLine());
	}
}
