<?php
namespace Logger\Filters;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class MaxLogLevelFilterTest extends TestCase {
	public function test(): void {
		$testLogger = new TestLogger();
		$logger = new MaxLogLevelFilter($testLogger, LogLevel::WARNING);

		$logger->error('test');
		self::assertNotEquals('test', $testLogger->getLastLine()->getMessage());

		$logger->warning('test');
		self::assertEquals('test', $testLogger->getLastLine()->getMessage());
	}
}
