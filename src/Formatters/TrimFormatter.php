<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;
use Psr\Log\LoggerInterface;
use Stringable;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class TrimFormatter extends AbstractLoggerAware implements BuilderAware {
	/**
	 * @return int
	 */
	public static function getWeight(): int {
		return 0;
	}

	public static function wrap(LoggerInterface $logger): self {
		return new self($logger);
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$message = trim($message);
		$this->logger()->log($level, $message, $context);
	}
}
