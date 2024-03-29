<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;
use Psr\Log\LoggerInterface;

class CallbackFormatter extends AbstractLoggerAware implements BuilderAware {
	/** @var callable(string, string, array<string, mixed>): string */
	private $fn;

	/**
	 * @return int
	 */
	public static function getWeight(): int {
		return 0;
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
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []) {
		$message = (string) call_user_func($this->fn, $level, $message, $context);
		$this->logger()->log($level, $message, $context);
	}
}
