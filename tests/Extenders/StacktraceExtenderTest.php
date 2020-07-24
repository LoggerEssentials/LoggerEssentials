<?php
namespace Logger\Extenders;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;

class StacktraceExtenderTest extends TestCase {
	public function test() {
		$testLogger = new TestLogger();
		$logger = new StacktraceExtender($testLogger, 'stacktrace');
		$logger->info('Hello world');
		$context = $testLogger->getLastLine()->getContext();
		$this->assertArrayHasKey('stacktrace', $context);
	}
}
