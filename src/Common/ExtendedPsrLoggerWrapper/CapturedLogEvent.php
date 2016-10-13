<?php
namespace Logger\Common\ExtendedPsrLoggerWrapper;

use Psr\Log\LoggerInterface;

class CapturedLogEvent {
	/** @var string */
	private $level;
	/** @var string */
	private $message;
	/** @var string */
	private $context;
	/** @var LoggerInterface */
	private $parentLogger;
	
	/**
	 * @param string $level
	 * @param string $message
	 * @param string $context
	 * @param LoggerInterface $parentLogger
	 */
	public function __construct($level, $message, $context, LoggerInterface $parentLogger) {
		$this->level = $level;
		$this->message = $message;
		$this->context = $context;
		$this->parentLogger = $parentLogger;
	}
	
	/**
	 * @return string
	 */
	public function getLevel() {
		return $this->level;
	}
	
	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}
	
	/**
	 * @return string
	 */
	public function getContext() {
		return $this->context;
	}
	
	/**
	 * @return LoggerInterface
	 */
	public function getParentLogger() {
		return $this->parentLogger;
	}
}
