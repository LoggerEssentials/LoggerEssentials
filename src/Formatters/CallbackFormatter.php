<?php
namespace Logger\Formatters;

use Closure;
use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;
use Psr\Log\LoggerInterface;

class CallbackFormatter extends AbstractLoggerAware implements BuilderAware {
	/** @var callable */
	private $fn;

	/**
	 * @return int
	 */
	public static function getWeight(): int {
		return 0;
	}

	/**
	 * @param LoggerInterface $logger
	 * @param Closure $fn
	 */
	public function __construct(LoggerInterface $logger, $fn) {
		parent::__construct($logger);
		$this->fn = $fn;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []) {
		$message = call_user_func($this->fn, $level, $message, $context);
		$this->logger()->log($level, $message, $context);
	}
}
