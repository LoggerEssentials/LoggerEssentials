<?php
namespace Logger\Common;

class ExtendedPsrLoggerWrapperTest extends \PHPUnit_Framework_TestCase {
	public function test() {
		$testLogger = new TestLogger();
		$logger = new ExtendedPsrLoggerWrapper($testLogger);
		$subLogger = $logger->createSubLogger('test');
		$subLogger->info('hello world');
		$this->assertEquals('test: hello world', $testLogger->getLastLine());
	}
}
