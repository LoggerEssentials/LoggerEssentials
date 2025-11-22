<?php

namespace Logger;

use PHPUnit\Framework\TestCase;
use Logger\Formatters\CallbackFormatter;
use Psr\Log\LoggerInterface;
use Logger\Loggers\ResourceLogger;

/**
 * {@see Builder}
 */
class BuilderTest extends TestCase {
	public function testChainWithSingleLogger(): void {
		$logger = ResourceLogger::outputToStdOut();
		$result = Builder::chain($logger);

		$this->assertSame($logger, $result);
	}

	public function testChainWithMultipleFormatters(): void {
		$logger = ResourceLogger::outputToStdOut();
		$result = Builder::chain(
			$logger,
			static fn(LoggerInterface $logger) => new CallbackFormatter($logger, fn(string $level, string $message, array $context) => $message),
			static fn(LoggerInterface $logger) => new CallbackFormatter($logger, fn(string $level, string $message, array $context) => $message)
		);

		$this->assertInstanceOf(LoggerInterface::class, $result);
	}
}
