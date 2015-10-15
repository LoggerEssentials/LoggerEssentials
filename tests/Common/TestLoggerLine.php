<?php
namespace Logger\Common;

class TestLoggerLine {
	/** @var string */
	private $message;
	/** @var array */
	private $context;
	/** @var int */
	private $severty;

	/**
	 * @param string $message
	 * @param array $context
	 * @param int $severty
	 */
	public function __construct($message, $context, $severty) {
		$this->message = $message;
		$this->context = $context;
		$this->severty = $severty;
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
		return $this->severty;
	}
}
