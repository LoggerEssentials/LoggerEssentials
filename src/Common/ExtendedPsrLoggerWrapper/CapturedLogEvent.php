<?php
namespace Logger\Common\ExtendedPsrLoggerWrapper;

use Psr\Log\LoggerInterface;

class CapturedLogEvent {
	/** @var string */
	private $level;
	/** @var string */
	private $message;
	/** @var array<string, mixed> */
	private $context;
	/** @var LoggerInterface */
	private $parentLogger;

	/**
	 * @param string $level
	 * @param string $message
	 * @param array<string, mixed> $context
	 * @param LoggerInterface $parentLogger
	 */
	public function __construct(string $level, string $message, array $context, LoggerInterface $parentLogger) {
		$this->level = $level;
		$this->message = $message;
		$this->context = $context;
		$this->parentLogger = $parentLogger;
	}

	/**
	 * @return string
	 */
	public function getLevel(): string {
		return $this->level;
	}

	/**
	 * @return string
	 */
	public function getMessage(): string {
		return $this->message;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function getContext(): array {
		return $this->context;
	}

	/**
	 * @return LoggerInterface
	 */
	public function getParentLogger(): LoggerInterface {
		return $this->parentLogger;
	}
}
