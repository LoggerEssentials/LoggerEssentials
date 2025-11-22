<?php
namespace Logger\Loggers;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\AbstractLogger;
use Stringable;
use Throwable;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class CallbackLogger extends AbstractLogger {
	/** @var callable(string, string, array<string, mixed>): void */
	private $callable;

	/**
	 * @param callable(string, string, array<string, mixed>): void $callable
	 */
	public static function wrap(callable $callable): self {
		return new self($callable);
	}

	/**
	 * @param callable(string, string, array<string, mixed>): void $callable
	 */
	public function __construct(callable $callable) {
		$this->callable = $callable;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		try {
			call_user_func($this->callable, $level, $message, $context);
		} catch(Throwable $e) {
		}
	}
}
