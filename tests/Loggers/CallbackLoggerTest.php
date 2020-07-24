<?php
namespace Logger\Loggers;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class CallbackLoggerTest extends TestCase {
	public function test() {
		$obj = (object) array();

		$callable = function ($level, $message, array $context = array()) use ($obj) {
			$obj->level = $level;
			$obj->message = $message;
			$obj->context = $context;
		};

		$logger = new CallbackLogger($callable);

		$logger->info('hello world', array('a' => 'b'));

		$this->assertEquals(LogLevel::INFO, $obj->level);
		$this->assertEquals('hello world', $obj->message);
		$this->assertEquals(array('a' => 'b'), $obj->context);
	}
}
