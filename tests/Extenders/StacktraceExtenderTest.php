<?php
namespace Logger\Extenders;

use Logger\Common\TestLogger;

class StacktraceExtenderTest extends \PHPUnit_Framework_TestCase {
	public function test() {
		$testLogger = new TestLogger();
		$logger = new StacktraceExtender($testLogger, 'stacktrace');
		$logger->info('Hello world');
		$context = $testLogger->getLastContext();
		$this->assertArrayHasKey('stacktrace', $context);
	}
}
