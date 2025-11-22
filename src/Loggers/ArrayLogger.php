<?php
namespace Logger\Loggers;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\AbstractLogger;
use Stringable;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class ArrayLogger extends AbstractLogger {
	/** @var array<int, array{level: string, message: string, context: array<string, mixed>}> */
	private $lines = [];

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$this->lines[] = [
			'level' => $level,
			'message' => $message,
			'context' => $context,
		];
	}

	/**
	 * @return array<int, array{level: string, message: string, context: array<string, mixed>}>
	 */
	public function getMessages(): array {
		return $this->lines;
	}

	/**
	 * @return $this
	 */
	public function clearAll(): self {
		$this->lines = [];
		return $this;
	}
}
