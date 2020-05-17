<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;
use Psr\Log\LoggerInterface;
use stdClass;

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
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []) {
		$ctx = $context;
		if(!count($ctx)) {
			$ctx = new stdClass();
		}
		$message = sprintf($this->format, $message, json_encode($ctx, $this->jsonOptions));
		$this->logger()->log($level, $message, $context);
	}
}
