<?php
namespace Logger\Extenders;

use Logger\Common\TestLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class CallbackExtenderTest extends TestCase {
	public function testAll(): void {
		$testLogger = new TestLogger();
		$logger = new CallbackExtender($testLogger, function ($level, &$message): void {
			if($level === LogLevel::INFO) {
				$message = preg_replace('/\\bworld\\b/', 'planet', $message);
			}
		});
		$logger->info('Hello world');
		self::assertEquals('Hello planet', $testLogger->getLastLine()->getMessage());
	}
}
