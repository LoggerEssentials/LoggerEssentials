<?php
namespace Logger\Filters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class CallbackFilter extends AbstractLoggerAware {
	/** @var callable(string, string, array<string, mixed>): bool */
	private $callback;

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
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = array()): void {
		$result = (bool) call_user_func($this->callback, $level, $message, $context);
		if($result) {
			$this->logger()->log($level, $message, $context);
		}
	}
}
