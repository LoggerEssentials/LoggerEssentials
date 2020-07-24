<?php
namespace Logger\Extenders;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;

class ContextExtenderTest extends TestCase {
	public function testAll() {
		$testLogger = new TestLogger();
		$logger = new ContextExtender($testLogger, array('test2' => 456));
		$logger->info('Hello world', array('test1' => 123));
		$this->assertEquals(array('test1' => 123, 'test2' => 456), $testLogger->getLastLine()->getContext());
	}
}
