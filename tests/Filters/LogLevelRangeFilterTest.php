<?php
namespace Logger\Filters;

use Logger\Common\TestLogger;
use Psr\Log\LogLevel;

class LogLevelRangeFilterTest extends \PHPUnit_Framework_TestCase {
	public function test() {
		$testLogger = new TestLogger();
		$logger = new LogLevelRangeFilter($testLogger, LogLevel::DEBUG, LogLevel::WARNING);

		$logger->error('error');
		$this->assertNotEquals('error', $testLogger->getLastLine()->getMessage());

		$logger->alert('alert');
		$this->assertNotEquals('alert', $testLogger->getLastLine()->getMessage());

		$logger->warning('warning');
		$this->assertEquals('warning', $testLogger->getLastLine()->getMessage());

		$logger->debug('debug');
		$this->assertEquals('debug', $testLogger->getLastLine()->getMessage());
	}
}
