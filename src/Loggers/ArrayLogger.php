<?php
namespace Logger\Loggers;

use Logger\Logger;
use Psr\Log\AbstractLogger;

class ArrayLogger extends AbstractLogger implements Logger {
	/** @var array */
	private $lines = array();

	/**
	 * Logs with an arbitrary level.
	 * @param string $level See Psr\Log\LogLevel::*
	 * @param string $message The message MUST be a string or object implementing __toString().
	 * @param array $context The context array can contain arbitrary data, the only assumption that can be made by implementors is that if an Exception instance is given to produce a stack trace, it MUST be in a key named "exception".
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$this->lines[] = array(
			'level' => $level,
			'message' => $message,
			'context' => $context,
		);
	}

	/**
	 * @return array[]
	 */
	public function getMessages() {
		return $this->lines;
	}

	/**
	 * @return $this
	 */
	public function clearAll() {
		$this->lines = array();
		return $this;
	}
}