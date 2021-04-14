<?php
namespace Logger\Extenders;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;

class ContextExtenderTest extends TestCase {
	public function testAll(): void {
		$testLogger = new TestLogger();
		$logger = new ContextExtender($testLogger, ['test2' => 456]);
		$logger->info('Hello world', ['test1' => 123]);
		self::assertEquals(['test1' => 123, 'test2' => 456], $testLogger->getLastLine()->getContext());
	}
}
