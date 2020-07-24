<?php
namespace Logger\Filters;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class CallbackFilterTest extends TestCase {
	public function test() {
		$testLogger = new TestLogger();
		$logger = new CallbackFilter($testLogger, function ($level) {
			return $level !== LogLevel::WARNING;
		});

		$logger->debug('debug');
		$this->assertEquals('debug', $testLogger->getLastLine()->getMessage());

		$logger->notice('notice');
		$this->assertEquals('notice', $testLogger->getLastLine()->getMessage());

		$logger->warning('warning');
		$this->assertNotEquals('warning', $testLogger->getLastLine()->getMessage());

		$logger->error('error');
		$this->assertEquals('error', $testLogger->getLastLine()->getMessage());
	}
}
