<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;

class PassThroughFormatter extends AbstractLoggerAware {
	/**
	 * Logs with an arbitrary level.
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$this->logger()->log($level, $message, $context);
	}
}
