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
class NobrFormatter extends AbstractLoggerAware implements BuilderAware {
	/** @var string */
	private $replacement;

	/**
	 * @return int
	 */
	public static function getWeight(): int {
		return 0;
	}

	public static function wrap(LoggerInterface $logger, string $replacement = ' '): self {
		return new self($logger, $replacement);
	}

	/**
	 * @param LoggerInterface $logger
	 * @param string $replacement
	 */
	public function __construct(LoggerInterface $logger, $replacement = ' ') {
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
		$message = (string) preg_replace("/[\r\n]+/", $this->replacement, $message);
		$this->logger()->log($level, $message, $context);
	}
}
