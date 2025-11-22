<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class ReplaceFormatter extends AbstractLoggerAware implements BuilderAware {
	/** @var array<string|int, string|int> */
	private $replacement;

	/**
	 * @return int
	 */
	public static function getWeight(): int {
		return 0;
	}

	/**
	 * @param LoggerInterface $logger
	 * @param array<string|int, string|int> $replacement
	 */
	public function __construct(LoggerInterface $logger, array $replacement) {
		parent::__construct($logger);
		$this->replacement = $replacement;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$message = strtr($message, $this->replacement);
		$this->logger()->log($level, $message, $context);
	}
}
