<?php
namespace Logger\Wrappers;

use Logger\Loggers\ArrayLogger;

class BufferedLoggerTest extends \PHPUnit_Framework_TestCase {
	public function testBufferAllNoFlush() {
		$arrayLogger = new ArrayLogger();
		$logger = new BufferedLogger($arrayLogger);
		$logger->info('Test A');
		$logger->info('Test B');
		$logger->info('Test C');

		$messages = $arrayLogger->getMessages();
		$this->assertCount(0, $messages);
	}

	public function testBufferAllPartialFlush() {
		$arrayLogger = new ArrayLogger();
		$logger = new BufferedLogger($arrayLogger);
		$logger->info('Test A');
		$logger->info('Test B');
		$logger->flush();
		$logger->info('Test C');

		$messages = $arrayLogger->getMessages();
		$this->assertCount(2, $messages);
		$this->assertEquals('Test A', $messages[0]['message']);
		$this->assertEquals('Test B', $messages[1]['message']);
	}

	public function testBufferAllFullFlush() {
		$arrayLogger = new ArrayLogger();
		$logger = new BufferedLogger($arrayLogger);
		$logger->info('Test A');
		$logger->info('Test B');
		$logger->info('Test C');
		$logger->flush();

		$messages = $arrayLogger->getMessages();
		$this->assertCount(3, $messages);
		$this->assertEquals('Test A', $messages[0]['message']);
		$this->assertEquals('Test B', $messages[1]['message']);
		$this->assertEquals('Test C', $messages[2]['message']);
	}

	public function testBufferNothingNoFlush() {
		$arrayLogger = new ArrayLogger();
		$logger = new BufferedLogger($arrayLogger, 0);
		$logger->info('Test A');
		$logger->info('Test B');
		$logger->info('Test C');

		$messages = $arrayLogger->getMessages();
		$this->assertCount(3, $messages);
		$this->assertEquals('Test A', $messages[0]['message']);
		$this->assertEquals('Test B', $messages[1]['message']);
		$this->assertEquals('Test C', $messages[2]['message']);
	}

	public function testBufferTwoNoFlush() {
		$arrayLogger = new ArrayLogger();
		$logger = new BufferedLogger($arrayLogger, 2);
		$logger->info('Test A');
		$logger->info('Test B');
		$logger->info('Test C');

		$messages = $arrayLogger->getMessages();
		$this->assertCount(2, $messages);
		$this->assertEquals('Test A', $messages[0]['message']);
		$this->assertEquals('Test B', $messages[1]['message']);
	}
}
