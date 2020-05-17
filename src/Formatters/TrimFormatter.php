<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;

class TrimFormatter extends AbstractLoggerAware {
	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = array()) {
		$message = trim($message);
		$this->logger()->log($level, $message, $context);
	}
}
