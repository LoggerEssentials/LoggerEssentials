<?php
namespace Logger\Common;

class TestLoggerLine {
	/** @var string|null */
	private $message;
	/** @var array */
	private $context;
	/** @var int|null */
	private $severity;

	/**
	 * @param string|null $message
	 * @param array $context
	 * @param int|null $severity
	 */
	public function __construct($message, $context, $severity) {
		$this->message = $message;
		$this->context = $context;
		$this->severity = $severity;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @return array
	 */
	public function getContext() {
		return $this->context;
	}

	/**
	 * @return int
	 */
	public function getSeverty() {
		return $this->severity;
	}
}
