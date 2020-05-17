<?php
namespace Logger\Loggers;

use Psr\Log\AbstractLogger;
use Throwable;

class CallbackLogger extends AbstractLogger {
	/** @var callable */
	private $callable;

	/**
	 * @param callable $callable
	 */
	public function __construct($callable) {
		$this->callable = $callable;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = array()) {
		try {
			call_user_func($this->callable, $level, $message, $context);
		} catch(Throwable $e) {
		}
	}
}
