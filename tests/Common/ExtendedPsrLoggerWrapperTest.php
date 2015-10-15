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

	public function testContextExtender() {
		$testLogger = new TestLogger();
		$logger = new ExtendedPsrLoggerWrapper($testLogger);
		$logger->context('context a', [], function () use ($logger) {
			$logger = $logger->createSubLogger('child a');
			$logger->context('context b', [], function () use ($logger) {
				$logger = $logger->createSubLogger('child b');
				$logger->info('Hello world');
			});
		});

		$this->assertEquals('context a > child a > context b > child b: Hello world', $testLogger->getLastLine());
	}
}
