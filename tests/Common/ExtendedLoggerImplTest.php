<?php
namespace Logger\Common;

use PHPUnit\Framework\TestCase;

class ExtendedLoggerImplTest extends TestCase {
	public function testSubLogger(): void {
		$testLogger = new TestLogger();
		$logger = new ExtendedLoggerImpl($testLogger);
		$subLogger = $logger->createSubLogger('test');
		$subLogger->info('hello world');
		self::assertEquals('test: hello world', $testLogger->getLastLine()->getMessage());
	}

	public function testContextExtenderSimple(): void {
		$testLogger = new TestLogger();
		$logger = new ExtendedLoggerImpl($testLogger);
		$logger->context('context a', [], function () use ($logger) {
			$logger->context('context b', [], function () use ($logger) {
				$logger->info('Hello world');
			});
		});

		self::assertEquals('context a > context b: Hello world', $testLogger->getLastLine()->getMessage());
	}

	public function testContextExtenderComplex(): void {
		$testLogger = new TestLogger();
		$logger = new ExtendedLoggerImpl($testLogger);
		$logger->context('context a', ['id' => 123], function () use ($logger) {
			$loggerA = $logger->createSubLogger('child a', ['id' => 456]);
			$loggerC = $logger->createSubLogger('child c', ['name' => 'Peter']);
			$logger->context('context b', ['id' => 789], function () use ($loggerA, $loggerC) {
				$loggerB = $loggerA->createSubLogger('child b', ['test' => 'abc']);
				$loggerB->info('Hello world');
				$loggerC->info('Hello world');
			});
		});

		self::assertEquals('context a > context b > child a > child b: Hello world', $testLogger->getFirstLine()->getMessage());
		self::assertEquals('context a > context b > child c: Hello world', $testLogger->getLastLine()->getMessage());
		self::assertEquals(['id' => 456, 'test' => 'abc'], $testLogger->getFirstLine()->getContext());
		self::assertEquals(['id' => 123, 'name' => 'Peter'], $testLogger->getLastLine()->getContext());
	}

	public function testMeasure(): void {
		$testLogger = new TestLogger();
		$logger = new ExtendedLoggerImpl($testLogger);
		$logger->measure('Test-Region', [], static function () {
			usleep(50);
		});

		$expectedPatterns = [
			'{Test-Region: Enter context}',
			'{Test-Region: Exit context: \\d+\\.\\d+ seconds}'
		];

		self::assertCount(count($expectedPatterns), $testLogger->getMessages());

		$messages = array_combine($expectedPatterns, $testLogger->getMessages());
		if($messages !== false) {
			foreach($messages as $expectedPattern => $actualMessage) {
				self::assertMatchesRegularExpression($expectedPattern, $actualMessage);
			}
		}
	}
}

