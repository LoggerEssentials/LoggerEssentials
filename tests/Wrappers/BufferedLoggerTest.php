<?php
namespace Logger\Wrappers;

use Logger\Loggers\ArrayLogger;
use PHPUnit\Framework\TestCase;

class BufferedLoggerTest extends TestCase {
	public function testBufferAllNoFlush(): void {
		$arrayLogger = new ArrayLogger();
		$logger = new BufferedLogger($arrayLogger);
		$logger->info('Test A');
		$logger->info('Test B');
		$logger->info('Test C');

		$messages = $arrayLogger->getMessages();
		self::assertCount(0, $messages);
	}

	public function testBufferAllPartialFlush(): void {
		$arrayLogger = new ArrayLogger();
		$logger = new BufferedLogger($arrayLogger);
		$logger->info('Test A');
		$logger->info('Test B');
		$logger->flush();
		$logger->info('Test C');

		$messages = $arrayLogger->getMessages();
		self::assertCount(2, $messages);
		self::assertEquals('Test A', $messages[0]['message']);
		self::assertEquals('Test B', $messages[1]['message']);
	}

	public function testBufferAllFullFlush(): void {
		$arrayLogger = new ArrayLogger();
		$logger = new BufferedLogger($arrayLogger);
		$logger->info('Test A');
		$logger->info('Test B');
		$logger->info('Test C');
		$logger->flush();

		$messages = $arrayLogger->getMessages();
		self::assertCount(3, $messages);
		self::assertEquals('Test A', $messages[0]['message']);
		self::assertEquals('Test B', $messages[1]['message']);
		self::assertEquals('Test C', $messages[2]['message']);
	}

	public function testBufferNothingNoFlush(): void {
		$arrayLogger = new ArrayLogger();
		$logger = new BufferedLogger($arrayLogger, 0);
		$logger->info('Test A');
		$logger->info('Test B');
		$logger->info('Test C');

		$messages = $arrayLogger->getMessages();
		self::assertCount(3, $messages);
		self::assertEquals('Test A', $messages[0]['message']);
		self::assertEquals('Test B', $messages[1]['message']);
		self::assertEquals('Test C', $messages[2]['message']);
	}

	public function testBufferTwoNoFlush(): void {
		$arrayLogger = new ArrayLogger();
		$logger = new BufferedLogger($arrayLogger, 2);
		$logger->info('Test A');
		$logger->info('Test B');
		$logger->info('Test C');

		$messages = $arrayLogger->getMessages();
		self::assertCount(2, $messages);
		self::assertEquals('Test A', $messages[0]['message']);
		self::assertEquals('Test B', $messages[1]['message']);
	}
}
