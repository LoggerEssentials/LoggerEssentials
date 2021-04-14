<?php
namespace Logger\Loggers;

use Psr\Log\AbstractLogger;
use Throwable;

class CallbackLogger extends AbstractLogger {
	/** @var callable(string, string, array<string, mixed>): void */
	private $callable;

	/**
	 * @param callable(string, string, array<string, mixed>): void $callable
	 */
	public function __construct(callable $callable) {
		$this->callable = $callable;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []): void {
		try {
			call_user_func($this->callable, $level, $message, $context);
		} catch(Throwable $e) {
		}
	}
}
