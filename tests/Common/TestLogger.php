<?php
namespace Logger\Common;

use Psr\Log\AbstractLogger;

class TestLogger extends AbstractLogger {
	/**
	 * @var string
	 */
	private $lastLevel;
	/**
	 * @var string
	 */
	private $lastLine = '';
	/**
	 * @var array
	 */
	private $lastContext;

	/**
	 * @return string
	 */
	public function lastLine() {
		return $this->lastLine;
	}

	/**
	 * Logs with an arbitrary level.
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$this->lastLevel = $level;
		$this->lastLine = $message;
		$this->lastContext = $context;
	}
}