<?php
namespace Logger\Loggers;

use Logger\Common\TestLogger;

class LoggerCollectionTest extends \PHPUnit_Framework_TestCase {
	public function testConstructor() {
		$testLogger1 = new TestLogger();
		$testLogger2 = new TestLogger();

		$loggerCollection = new LoggerCollection(array($testLogger1, $testLogger2));
		$loggerCollection->info('Hello world');

		$this->assertEquals('Hello world', $testLogger1->getLastLine());
		$this->assertEquals('Hello world', $testLogger2->getLastLine());
	}

	public function testAdd() {
		$testLogger1 = new TestLogger();
		$testLogger2 = new TestLogger();

		$loggerCollection = new LoggerCollection();
		$loggerCollection->add($testLogger1);
		$loggerCollection->add($testLogger2);
		$loggerCollection->info('Hello world');

		$this->assertEquals('Hello world', $testLogger1->getLastLine());
		$this->assertEquals('Hello world', $testLogger2->getLastLine());
	}

	public function testBoth() {
		$testLogger1 = new TestLogger();
		$testLogger2 = new TestLogger();

		$loggerCollection = new LoggerCollection(array($testLogger1));
		$loggerCollection->add($testLogger2);
		$loggerCollection->info('Hello world');

		$this->assertEquals('Hello world', $testLogger1->getLastLine());
		$this->assertEquals('Hello world', $testLogger2->getLastLine());
	}
}
