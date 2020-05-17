<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;

class PassThroughFormatter extends AbstractLoggerAware {
	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = array()) {
		$this->logger()->log($level, $message, $context);
	}
}
