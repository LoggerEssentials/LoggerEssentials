<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;

class TrimFormatter extends AbstractLoggerAware implements BuilderAware {
	/**
	 * @return int
	 */
	public static function getWeight(): int {
		return 0;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []) {
		$message = trim($message);
		$this->logger()->log($level, $message, $context);
	}
}
