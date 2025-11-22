<?php
namespace Logger\Common;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class DummyAware extends AbstractLoggerAware {
	public function __construct(LoggerInterface $logger) {
		parent::__construct($logger);
	}

	public function poke(): void {
		$this->logger()->info('ok');
	}

	/**
	 * @param mixed $level
	 * @param string $message
	 * @param array<string, mixed> $context
	 */
	public function log($level, $message, array $context = []): void {
		$this->logger()->log($level, $message, $context);
	}
}

class AbstractLoggerAwareTest extends TestCase {
	public function testLoggerIsInjectedAndAccessible(): void {
		$test = new TestLogger();
		$aware = new DummyAware($test);
		$aware->poke();
		self::assertSame('ok', $test->getLastLine()->getMessage());
	}
}
