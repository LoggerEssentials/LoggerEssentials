<?php
namespace Logger\Filters;

use Logger\Common\TestLogger;
use Psr\Log\LogLevel;

class MinLogLevelFilterTest extends \PHPUnit_Framework_TestCase {
	public function test() {
		$testLogger = new TestLogger();
		$logger = new MinLogLevelFilter($testLogger, LogLevel::ERROR);

		$logger->warning('test');
		$this->assertNotEquals('test', $testLogger->getLastLine());

		$logger->error('test');
		$this->assertEquals('test', $testLogger->getLastLine());
	}
}
