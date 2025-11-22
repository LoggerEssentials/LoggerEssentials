<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class CallbackFormatter extends AbstractLoggerAware implements BuilderAware {
	/** @var callable(string, string, array<string, mixed>): string */
	private $fn;

	/**
	 * @return int
	 */
	public static function getWeight(): int {
		return 0;
	}

	public static function wrap(LoggerInterface $logger, callable $fn): self {
		return new self($logger, $fn);
	}

	/**
	 * @param LoggerInterface $logger
	 * @param callable(string, string, array<string, mixed>): string $fn
	 */
	public function __construct(LoggerInterface $logger, callable $fn) {
		parent::__construct($logger);
		$this->fn = $fn;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$message = (string) call_user_func($this->fn, $level, $message, $context);
		$this->logger()->log($level, $message, $context);
	}
}
