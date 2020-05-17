<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;
use Psr\Log\LoggerInterface;

class FormatFormatter extends AbstractLoggerAware implements BuilderAware {
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
	 * @param string $format
	 */
	public function __construct(LoggerInterface $logger, $format = "%s") {
		parent::__construct($logger);
		$this->format = $format;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []) {
		$message = sprintf($this->format, $message);
		$this->logger()->log($level, $message, $context);
	}
}
