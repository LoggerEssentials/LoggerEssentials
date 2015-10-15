<?php
namespace Logger\Common;

class ExtendedPsrLoggerWrapperTest extends \PHPUnit_Framework_TestCase {
	public function testSubLogger() {
		$testLogger = new TestLogger();
		$logger = new ExtendedPsrLoggerWrapper($testLogger);
		$subLogger = $logger->createSubLogger('test');
		$subLogger->info('hello world');
		$this->assertEquals('test: hello world', $testLogger->getLastLine()->getMessage());
	}

	public function testContextExtenderSimple() {
		$testLogger = new TestLogger();
		$logger = new ExtendedPsrLoggerWrapper($testLogger);
		$logger->context('context a', array(), function () use ($logger) {
			$logger->context('context b', array(), function () use ($logger) {
				$logger->info('Hello world');
			});
		});

		$this->assertEquals('context a > context b: Hello world', $testLogger->getLastLine()->getMessage());
	}

	public function testContextExtenderComplex() {
		$testLogger = new TestLogger();
		$logger = new ExtendedPsrLoggerWrapper($testLogger);
		$logger->context('context a', array(), function () use ($logger) {
			$logger = $logger->createSubLogger('child a');
			$loggerC = $logger->createSubLogger('child c');
			$logger->context('context b', array(), function () use ($logger, $loggerC) {
				$loggerB = $logger->createSubLogger('child b');
				$loggerB->info('Hello world');
				$loggerC->info('Hello world');
			});
		});

		$this->assertEquals('context a > child a > context b > child b: Hello world', $testLogger->getFirstLine()->getMessage());
		$this->assertEquals('context a > child a > context b > child c: Hello world', $testLogger->getLastLine()->getMessage());
	}
}

