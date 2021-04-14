<?php
namespace Logger\Common;

class TestLoggerLine {
	/** @var string|null */
	private $message;
	/** @var array<string, mixed> */
	private $context;
	/** @var string|null */
	private $severity;

	/**
	 * @param string|null $message
	 * @param array<string, mixed> $context
	 * @param string|null $severity
	 */
	public function __construct(?string $message, array $context, ?string $severity) {
		$this->message = $message;
		$this->context = $context;
		$this->severity = $severity;
	}

	/**
	 * @return string|null
	 */
	public function getMessage(): ?string {
		return $this->message;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function getContext(): array {
		return $this->context;
	}

	/**
	 * @return string|null
	 */
	public function getSeverty(): ?string {
		return $this->severity;
	}
}
