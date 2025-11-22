<?php
namespace Logger\Loggers;

use PHPUnit\Framework\TestCase;

// Namespace-level stub for error_log to capture calls
/** @noinspection PhpUnused */
function error_log($message, $message_type = 0, $destination = null, $extra_headers = null) {
	ErrorLogSpy::$calls[] = [$message, $message_type, $destination, $extra_headers];
	if(is_string($message) && str_contains($message, 'throw')) {
		throw new \RuntimeException('Simulated error_log failure');
	}
}

class ErrorLogSpy {
	/** @var array<int, array{0: mixed, 1: mixed, 2: mixed, 3: mixed}> */
	public static array $calls = [];

	public static function reset(): void {
		self::$calls = [];
	}
}

class ErrorLogLoggerTest extends TestCase {
	protected function setUp(): void {
		ErrorLogSpy::reset();
	}

	public function testDefaultMessageTypeZero(): void {
		$logger = new ErrorLogLogger(0);
		$logger->info('hello');
		self::assertNotEmpty(ErrorLogSpy::$calls);
		[$message, $type] = ErrorLogSpy::$calls[0];
		self::assertSame('hello', $message);
		self::assertSame(0, $type);
	}

	public function testMessageTypeFour(): void {
		ErrorLogSpy::reset();
		$logger = new ErrorLogLogger(4);
		$logger->info('world');
		[$message, $type] = ErrorLogSpy::$calls[0];
		self::assertSame('world', $message);
		self::assertSame(4, $type);
	}

	public function testMessageTypeOneWithDestinationAndHeaders(): void {
		ErrorLogSpy::reset();
		$logger = new ErrorLogLogger(1, 'dest.log', 'X-Test: 1');
		$logger->warning('test');
		[$message, $type, $dest, $hdrs] = ErrorLogSpy::$calls[0];
		self::assertSame('test', $message);
		self::assertSame(1, $type);
		self::assertSame('dest.log', $dest);
		self::assertSame('X-Test: 1', $hdrs);
	}

	public function testErrorsAreCaught(): void {
		ErrorLogSpy::reset();
		$logger = new ErrorLogLogger(0);
		// Should not throw though the stub will throw internally
		$logger->error('please-throw');
		self::assertNotEmpty(ErrorLogSpy::$calls);
	}
}

