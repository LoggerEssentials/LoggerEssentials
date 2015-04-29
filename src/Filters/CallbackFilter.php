<?php
namespace Logger\Filters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class CallbackFilter extends AbstractLoggerAware {
	/** @var callable */
	private $callback = null;

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
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$result = call_user_func($this->callback, $level, $message, $context);
		if($result) {
			$this->logger()->log($level, $message, $context);
		}
	}
}
