<?php
namespace Logger\Common;

use Logger\Common\ExtendedPsrLoggerWrapper\CapturedLogEvent;

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
		$logger->context('context a', array('id' => 123), function () use ($logger) {
			$loggerA = $logger->createSubLogger('child a', array('id' => 456));
			$loggerC = $logger->createSubLogger('child c', array('name' => 'Peter'));
			$logger->context('context b', array('id' => 789), function () use ($loggerA, $loggerC) {
				$loggerB = $loggerA->createSubLogger('child b', array('test' => 'abc'));
				$loggerB->info('Hello world');
				$loggerC->info('Hello world');
			});
		});

		$this->assertEquals('context a > context b > child a > child b: Hello world', $testLogger->getFirstLine()->getMessage());
		$this->assertEquals('context a > context b > child c: Hello world', $testLogger->getLastLine()->getMessage());
		$this->assertEquals(array('id' => 456, 'test' => 'abc'), $testLogger->getFirstLine()->getContext());
		$this->assertEquals(array('id' => 123, 'name' => 'Peter'), $testLogger->getLastLine()->getContext());
	}

	public function testCapture() {
		$testLogger = new TestLogger();
		$logger = new ExtendedPsrLoggerWrapper($testLogger);
		$logger->intercept(function () use ($logger) {
			$logger->info('Hello world');
		}, function (CapturedLogEvent $logEvent) {
			$logEvent->getParentLogger()->log($logEvent->getLevel(), strtoupper($logEvent->getMessage()), $logEvent->getContext());
		});

		$this->assertEquals('info', $testLogger->getLastLine()->getSeverty());
		$this->assertEquals('HELLO WORLD', $testLogger->getLastLine()->getMessage());
		$this->assertEquals(array(), $testLogger->getLastLine()->getContext());
	}
}

