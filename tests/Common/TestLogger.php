<?php
namespace Logger\Common;

use Psr\Log\AbstractLogger;

class TestLogger extends AbstractLogger {
	/** @var array<int, array{string, array<string, mixed>, string}> */
	private $lines = [];

	/**
	 * @return string[]
	 */
	public function getMessages(): array {
		$messages = [];
		foreach($this->lines as $line) {
			$messages[] = $line[0];
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
	 * @param string $level
	 * @param string $message
	 * @param array<string, mixed> $context
	 * @return void
	 */
	public function log($level, $message, array $context = []) {
		$this->lines[] = [$message, $context, $level];
	}
}
