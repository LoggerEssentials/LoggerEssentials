<?php
namespace Logger\Loggers;

use Psr\Log\AbstractLogger;

class NullLogger extends AbstractLogger {
	/**
	 * Logs with an arbitrary level.
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
	}
}