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
class FormatFormatter extends AbstractLoggerAware implements BuilderAware {
	/** @var string */
	private $format;

	/**
	 * @return int
	 */
	public static function getWeight(): int {
		return 0;
	}

	public static function wrap(LoggerInterface $logger, string $format = "%s"): self {
		return new self($logger, $format);
	}

	/**
	 * @param LoggerInterface $logger
	 * @param string $format
	 */
	public function __construct(LoggerInterface $logger, $format = "%s") {
		parent::__construct($logger);
		$this->format = $format;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$message = sprintf($this->format, $message);
		$this->logger()->log($level, $message, $context);
	}
}
