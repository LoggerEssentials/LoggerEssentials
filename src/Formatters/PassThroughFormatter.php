<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;

class PassThroughFormatter extends AbstractLoggerAware implements BuilderAware {
	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []) {
		$this->logger()->log($level, $message, $context);
	}
}
