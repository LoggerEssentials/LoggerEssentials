<?php
namespace Logger\Loggers;

use Psr\Log\AbstractLogger;

class ArrayLogger extends AbstractLogger {
	/** @var array */
	private $lines = array();

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = array()) {
		$this->lines[] = [
			'level' => $level,
			'message' => $message,
			'context' => $context,
		];
	}

	/**
	 * @return array[]
	 */
	public function getMessages(): array {
		return $this->lines;
	}

	/**
	 * @return $this
	 */
	public function clearAll() {
		$this->lines = [];
		return $this;
	}
}
