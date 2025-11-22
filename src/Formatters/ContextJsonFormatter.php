<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;
use Psr\Log\LoggerInterface;
use stdClass;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class ContextJsonFormatter extends AbstractLoggerAware implements BuilderAware {
	/** @var int */
	private $jsonOptions;
	/** @var string */
	private $format;

	/**
	 * @return int
	 */
	public static function getWeight(): int {
		return 0;
	}

	/**
	 * @param LoggerInterface $logger
	 * @param int $jsonOptions
	 * @param string $format
	 */
	public function __construct(LoggerInterface $logger, $jsonOptions = 0, $format = '%s %s') {
		parent::__construct($logger);
		$this->jsonOptions = $jsonOptions;
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
		$ctx = $context;
		if(!count($ctx)) {
			$ctx = new stdClass();
		}
		$message = sprintf($this->format, $message, json_encode($ctx, $this->jsonOptions));
		$this->logger()->log($level, $message, $context);
	}
}
