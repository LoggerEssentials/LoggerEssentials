<?php
namespace Logger\Filters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class CallbackFilter extends AbstractLoggerAware {
	/** @var callable(string, string, array<string, mixed>): bool */
	private $callback;

	public static function wrap(LoggerInterface $logger, callable $callback): self {
		return new self($logger, $callback);
	}

	/**
	 * @param LoggerInterface $logger
	 * @param callable(string, string, array<string, mixed>): bool $callback
	 */
	public function __construct(LoggerInterface $logger, callable $callback) {
		parent::__construct($logger);
		$this->callback = $callback;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$result = (bool) call_user_func($this->callback, $level, $message, $context);
		if($result) {
			$this->logger()->log($level, $message, $context);
		}
	}
}
