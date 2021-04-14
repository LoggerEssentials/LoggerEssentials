<?php
namespace Logger\Filters;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class ExcludeLogLevelFilterTest extends TestCase {
	public function test(): void {
		$testLogger = new TestLogger();
		$logger = new ExcludeLogLevelFilter($testLogger, LogLevel::DEBUG);
		$logger->debug('Hello world');
		self::assertNotEquals('Hello world', $testLogger->getLastLine()->getMessage());
		$logger->info('Hello world');
		self::assertEquals('Hello world', $testLogger->getLastLine()->getMessage());
	}
}
