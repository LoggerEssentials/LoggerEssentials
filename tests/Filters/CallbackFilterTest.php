<?php
namespace Logger\Filters;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class CallbackFilterTest extends TestCase {
	public function test(): void {
		$testLogger = new TestLogger();
		$logger = new CallbackFilter($testLogger, function (string $level, string $message, array $context) {
			return $level !== LogLevel::WARNING;
		});

		$logger->debug('debug');
		self::assertEquals('debug', $testLogger->getLastLine()->getMessage());

		$logger->notice('notice');
		self::assertEquals('notice', $testLogger->getLastLine()->getMessage());

		$logger->warning('warning');
		self::assertNotEquals('warning', $testLogger->getLastLine()->getMessage());

		$logger->error('error');
		self::assertEquals('error', $testLogger->getLastLine()->getMessage());
	}
}
