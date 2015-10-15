<?php
namespace Logger\Common;

use Psr\Log\AbstractLogger;

class TestLogger extends AbstractLogger {
	/** @var array */
	private $firstLine = null;
	/** @var array */
	private $lastLine = null;

	/**
	 * @return TestLoggerLine
	 */
	public function getFirstLine() {
		return new TestLoggerLine($this->firstLine['message'], $this->firstLine['context'], $this->firstLine['severty']);
	}

	/**
	 * @return TestLoggerLine
	 */
	public function getLastLine() {
		return new TestLoggerLine($this->lastLine['message'], $this->lastLine['context'], $this->lastLine['severty']);
	}

	/**
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$this->lastLine = array(
			'message' => $message,
			'context' => $context,
			'severty' => $level,
		);
		if($this->firstLine === null) {
			$this->firstLine = $this->lastLine;
		}
	}
}
