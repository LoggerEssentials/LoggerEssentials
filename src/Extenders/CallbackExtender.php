<?php
namespace Logger\Extenders;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class CallbackExtender extends AbstractLoggerAware {
	/** @var callable(string, string, array<string, mixed>): void */
	private $callback;

	/**
	 * @param LoggerInterface $logger
	 * @param callable(string, string, array<string, mixed>): void $callback
	 */
	public function __construct(LoggerInterface $logger, callable $callback) {
		parent::__construct($logger);
		$this->callback = $callback;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param string $level
	 * @param string $message
	 * @param array<string, mixed> $context
	 */
	public function log($level, $message, array $context = []): void {
		$fn = $this->callback;
		$fn($level, $message, $context);
		$this->logger()->log($level, $message, $context);
	}
}
