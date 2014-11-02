<?php
namespace Logger\Filters;

use Logger\Common\TestLogger;
use Psr\Log\LogLevel;

class ExcludeLogLevelFilterTest extends \PHPUnit_Framework_TestCase {
	public function test() {
		$testLogger = new TestLogger();
		$logger = new ExcludeLogLevelFilter($testLogger, LogLevel::DEBUG);
		$logger->debug('Hello world');
		$this->assertNotEquals('Hello world', $testLogger->getLastLine());
		$logger->info('Hello world');
		$this->assertEquals('Hello world', $testLogger->getLastLine());
	}
}
