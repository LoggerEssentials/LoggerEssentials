<?php
namespace Logger\Filters;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class MaxLogLevelFilterTest extends TestCase {
	public function test() {
		$testLogger = new TestLogger();
		$logger = new MaxLogLevelFilter($testLogger, LogLevel::WARNING);

		$logger->error('test');
		$this->assertNotEquals('test', $testLogger->getLastLine()->getMessage());

		$logger->warning('test');
		$this->assertEquals('test', $testLogger->getLastLine()->getMessage());
	}
}
