<?php
namespace Logger\Filters;

use Logger\Common\TestLogger;
use Psr\Log\LogLevel;

class CallbackFilterTest extends \PHPUnit_Framework_TestCase {
	public function test() {
		$testLogger = new TestLogger();
		$logger = new CallbackFilter($testLogger, function ($level) {
			return $level !== LogLevel::WARNING;
		});

		$logger->debug('debug');
		$this->assertEquals('debug', $testLogger->getLastLine());

		$logger->notice('notice');
		$this->assertEquals('notice', $testLogger->getLastLine());

		$logger->warning('warning');
		$this->assertNotEquals('warning', $testLogger->getLastLine());

		$logger->error('error');
		$this->assertEquals('error', $testLogger->getLastLine());
	}
}
