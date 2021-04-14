<?php
namespace Logger\Filters;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class LogLevelRangeFilterTest extends TestCase {
	public function test(): void {
		$testLogger = new TestLogger();
		$logger = new LogLevelRangeFilter($testLogger, LogLevel::DEBUG, LogLevel::WARNING);

		$logger->error('error');
		self::assertNotEquals('error', $testLogger->getLastLine()->getMessage());

		$logger->alert('alert');
		self::assertNotEquals('alert', $testLogger->getLastLine()->getMessage());

		$logger->warning('warning');
		self::assertEquals('warning', $testLogger->getLastLine()->getMessage());

		$logger->debug('debug');
		self::assertEquals('debug', $testLogger->getLastLine()->getMessage());
	}
}
