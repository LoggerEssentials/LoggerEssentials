<?php
namespace Logger\Filters;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;

class RegularExpressionFilterTest extends TestCase {
	public function test(): void {
		$testLogger = new TestLogger();
		$logger = new RegularExpressionFilter($testLogger, 'is a', 'u');

		$logger->info('hello world');
		self::assertEquals('hello world', $testLogger->getLastLine()->getMessage());

		$logger->notice('this is a test');
		self::assertNotEquals('this is a test', $testLogger->getLastLine()->getMessage());
	}

	public function testNegate(): void {
		$testLogger = new TestLogger();
		$logger = new RegularExpressionFilter($testLogger, 'is a', 'u');

		$logger->info('hello world');
		self::assertEquals('hello world', $testLogger->getLastLine()->getMessage());

		$logger->notice('this is a test');
		self::assertNotEquals('this is a test', $testLogger->getLastLine()->getMessage());
	}
}
