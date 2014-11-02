<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class DateTimeFormatter extends AbstractLoggerAware {
	/**
	 * @var string
	 */
	private $dateFmt;
	/**
	 * @var string
	 */
	private $format;

	/**
	 * @param LoggerInterface $logger
	 * @param string $dateFmt
	 * @param string $format
	 */
	public function __construct(LoggerInterface $logger, $dateFmt = "[Y-m-d H:i:s] ", $format = '%s%s') {
		parent::__construct($logger);
		$this->dateFmt = $dateFmt;
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
		$message = sprintf($this->format, date($this->dateFmt), $message);
		$this->logger()->log($level, $message, $context);
	}
}