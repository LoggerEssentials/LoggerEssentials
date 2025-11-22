<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class PassThroughFormatter extends AbstractLoggerAware {
	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$this->logger()->log($level, $message, $context);
	}
}
