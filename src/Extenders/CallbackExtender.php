<?php
namespace Logger\Extenders;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class CallbackExtender extends AbstractLoggerAware {
	/** @var callable */
	private $callback;

	/**
	 * @param LoggerInterface $logger
	 * @param callable $callback
	 */
	public function __construct(LoggerInterface $logger, $callback) {
		parent::__construct($logger);
		$this->callback = $callback;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param $level
	 * @param $message
	 * @param array $context
	 */
	public function log($level, $message, array $context = array()) {
		$fn = $this->callback;
		$fn($level, $message, $context);
		$this->logger()->log($level, $message, $context);
	}
}
