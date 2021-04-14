<?php
namespace Logger\Loggers;

use Psr\Log\AbstractLogger;

class ArrayLogger extends AbstractLogger {
	/** @var array<int, array{level: string, message: string, context: array<string, mixed>}> */
	private $lines = [];

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
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
