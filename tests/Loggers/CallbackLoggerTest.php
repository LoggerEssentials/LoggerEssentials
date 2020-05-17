<?php
namespace Logger\Loggers;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class CallbackLoggerTest extends TestCase {
	public function test() {
		$obj = (object) [];

		$callable = static function ($level, $message, array $context = []) use ($obj) {
			$obj->level = $level;
			$obj->message = $message;
			$obj->context = $context;
		};

		$logger = new CallbackLogger($callable);

		$logger->info('hello world', ['a' => 'b']);

		$this->assertEquals(LogLevel::INFO, $obj->level);
		$this->assertEquals('hello world', $obj->message);
		$this->assertEquals(['a' => 'b'], $obj->context);
	}
}
