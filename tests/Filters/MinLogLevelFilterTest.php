<?php
namespace Logger\Filters;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class MinLogLevelFilterTest extends TestCase {
	public function test(): void {
		$testLogger = new TestLogger();
		$logger = new MinLogLevelFilter($testLogger, LogLevel::ERROR);

		$logger->warning('test');
		self::assertNotEquals('test', $testLogger->getLastLine()->getMessage());

		$logger->error('test');
		self::assertEquals('test', $testLogger->getLastLine()->getMessage());
	}
}
