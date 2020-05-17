<?php
namespace Logger\Common;

use Psr\Log\AbstractLogger;

class TestLogger extends AbstractLogger {
	/** @var array */
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
	 * @return TestLoggerLine|null
	 */
	public function getFirstLine() {
		if(count($this->lines) > 0) {
			list($message, $context, $severity) = $this->lines[0];
			return new TestLoggerLine($message ?? '', $context ?? [], $severity ?? '');
		}
		return new TestLoggerLine('', [], '');
	}

	/**
	 * @return TestLoggerLine|null
	 */
	public function getLastLine() {
		if(count($this->lines) > 0) {
			list($message, $context, $severity) = array_slice($this->lines, -1, 1)[0];
			return new TestLoggerLine($message ?? '', $context ?? [], $severity ?? '');
		}
		return new TestLoggerLine('', [], '');
	}

	/**
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = []) {
		$this->lines[] = [$message, $context, $level];
	}
}
