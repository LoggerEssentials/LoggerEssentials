<?php
namespace Logger\Loggers;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class ArrayLoggerTest extends TestCase {
	public function test(): void {
		$logger = new ArrayLogger();
		self::assertCount(0, $logger->getMessages());
		$logger->info('hello world');
		self::assertCount(1, $logger->getMessages());
		self::assertEquals([['level' => LogLevel::INFO, 'message' => 'hello world', 'context' => []]], $logger->getMessages());
		$logger->clearAll();
		self::assertCount(0, $logger->getMessages());
	}
}
