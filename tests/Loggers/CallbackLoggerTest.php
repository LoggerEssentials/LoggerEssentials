<?php
namespace Logger\Loggers;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class CallbackLoggerTest extends TestCase {
	public function test(): void {
		$obj = (object) [];

		$callable = static function ($level, $message, array $context = []) use ($obj) {
			$obj->level = $level;
			$obj->message = $message;
			$obj->context = $context;
		};

		$logger = new CallbackLogger($callable);

		$logger->info('hello world', ['a' => 'b']);

		self::assertEquals(LogLevel::INFO, $obj->level);
		self::assertEquals('hello world', $obj->message);
		self::assertEquals(['a' => 'b'], $obj->context);
	}
}
