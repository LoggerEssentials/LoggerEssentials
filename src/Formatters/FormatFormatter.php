<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class FormatFormatter extends AbstractLoggerAware {
	/**
	 * @var string
	 */
	private $format;

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
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$message = sprintf($this->format, $message);
		$this->logger()->log($level, $message, $context);
	}
}