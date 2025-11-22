<?php
namespace Logger\Common;

use Psr\Log\AbstractLogger;

class TestLogger extends AbstractLogger {
	/** @var array<int, array{null|string, null|array<string, mixed>, null|string}> */
	private $lines = [];

	/**
	 * @return string[]
	 */
	public function getMessages(): array {
		/** @var string[] $messages */
		$messages = [];
		foreach($this->lines as $line) {
			if($line[0] !== null) {
				$messages[] = $line[0];
			}
		}
		return $messages;
	}

	/**
	 * @return TestLoggerLine
	 */
	public function getFirstLine() {
		if(count($this->lines) > 0) {
			[$message, $context, $severity] = $this->lines[0];
			return new TestLoggerLine($message ?? '', $context ?? [], $severity ?? '');
		}
		return new TestLoggerLine('', [], null);
	}

	/**
	 * @return TestLoggerLine
	 */
	public function getLastLine() {
		if(count($this->lines) > 0) {
			[$message, $context, $severity] = array_slice($this->lines, -1, 1)[0];
			return new TestLoggerLine($message ?? '', $context ?? [], $severity ?? '');
		}
		return new TestLoggerLine('', [], null);
	}

	/**
	 * @param mixed $level
	 * @param string $message
	 * @param array<string, mixed> $context
	 * @return void
	 */
	public function log($level, $message, array $context = []): void {
		$this->lines[] = [$message, $context, is_string($level) ? $level : null];
	}
}
