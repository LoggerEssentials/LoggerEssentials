<?php
namespace Logger\Loggers;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;

class LoggerCollectionTest extends TestCase {
	public function testConstructor(): void {
		$testLogger1 = new TestLogger();
		$testLogger2 = new TestLogger();

		$loggerCollection = new LoggerCollection(array($testLogger1, $testLogger2));
		$loggerCollection->info('Hello world');

		self::assertEquals('Hello world', $testLogger1->getLastLine()->getMessage());
		self::assertEquals('Hello world', $testLogger2->getLastLine()->getMessage());
	}

	public function testAdd(): void {
		$testLogger1 = new TestLogger();
		$testLogger2 = new TestLogger();

		$loggerCollection = new LoggerCollection();
		$loggerCollection->add($testLogger1);
		$loggerCollection->add($testLogger2);
		$loggerCollection->info('Hello world');

		self::assertEquals('Hello world', $testLogger1->getLastLine()->getMessage());
		self::assertEquals('Hello world', $testLogger2->getLastLine()->getMessage());
	}

	public function testBoth(): void {
		$testLogger1 = new TestLogger();
		$testLogger2 = new TestLogger();

		$loggerCollection = new LoggerCollection(array($testLogger1));
		$loggerCollection->add($testLogger2);
		$loggerCollection->info('Hello world');

		self::assertEquals('Hello world', $testLogger1->getLastLine()->getMessage());
		self::assertEquals('Hello world', $testLogger2->getLastLine()->getMessage());
	}
}
