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
		$logger->context('context a', [], function () use ($logger) {
			$logger->context('context b', [], function () use ($logger) {
				$logger->info('Hello world');
			});
		});

		$this->assertEquals('context a > context b: Hello world', $testLogger->getLastLine()->getMessage());
	}

	public function testContextExtenderComplex() {
		$testLogger = new TestLogger();
		$logger = new ExtendedPsrLoggerWrapper($testLogger);
		$logger->context('context a', ['id' => 123], function () use ($logger) {
			$loggerA = $logger->createSubLogger('child a', ['id' => 456]);
			$loggerC = $logger->createSubLogger('child c', ['name' => 'Peter']);
			$logger->context('context b', ['id' => 789], function () use ($loggerA, $loggerC) {
				$loggerB = $loggerA->createSubLogger('child b', ['test' => 'abc']);
				$loggerB->info('Hello world');
				$loggerC->info('Hello world');
			});
		});

		$this->assertEquals('context a > context b > child a > child b: Hello world', $testLogger->getFirstLine()->getMessage());
		$this->assertEquals('context a > context b > child c: Hello world', $testLogger->getLastLine()->getMessage());
		$this->assertEquals(['id' => 456, 'test' => 'abc'], $testLogger->getFirstLine()->getContext());
		$this->assertEquals(['id' => 123, 'name' => 'Peter'], $testLogger->getLastLine()->getContext());
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
		$this->assertEquals([], $testLogger->getLastLine()->getContext());
	}

	public function testMeasure() {
		$testLogger = new TestLogger();
		$logger = new ExtendedPsrLoggerWrapper($testLogger);
		$logger->measure('Test-Region', [], static function () {
			usleep(50);
		});

		$expectedPatterns = [
			'/Test-Region: Enter context/',
			'/Test-Region: Exit context: \\d+\\.\\d+ seconds/'
		];

		$this->assertCount(count($expectedPatterns), $testLogger->getMessages());

		$messages = array_combine($expectedPatterns, $testLogger->getMessages());
		foreach($messages as $expectedPattern => $actualMessage) {
			$this->assertRegExp($expectedPattern, $actualMessage);
		}
	}
}

