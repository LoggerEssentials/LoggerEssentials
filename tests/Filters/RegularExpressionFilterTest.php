<?php
namespace Logger\Filters;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;

class RegularExpressionFilterTest extends TestCase {
	public function test() {
		$testLogger = new TestLogger();
		$logger = new RegularExpressionFilter($testLogger, 'is a', 'u');

		$logger->info('hello world');
		$this->assertEquals('hello world', $testLogger->getLastLine()->getMessage());

		$logger->notice('this is a test');
		$this->assertNotEquals('this is a test', $testLogger->getLastLine()->getMessage());
	}

	public function testNegate() {
		$testLogger = new TestLogger();
		$logger = new RegularExpressionFilter($testLogger, 'is a', 'u');

		$logger->info('hello world');
		$this->assertEquals('hello world', $testLogger->getLastLine()->getMessage());

		$logger->notice('this is a test');
		$this->assertNotEquals('this is a test', $testLogger->getLastLine()->getMessage());
	}
}
