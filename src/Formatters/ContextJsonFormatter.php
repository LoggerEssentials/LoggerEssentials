<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;
use stdClass;

class ContextJsonFormatter extends AbstractLoggerAware {
	/** @var int */
	private $jsonOptions;
	/** @var string */
	private $format;

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
	public function log($level, $message, array $context = array()) {
		$ctx = $context;
		if(!count($ctx)) {
			$ctx = new stdClass();
		}
		$message = sprintf($this->format, $message, json_encode($ctx, $this->jsonOptions));
		$this->logger()->log($level, $message, $context);
	}
}
