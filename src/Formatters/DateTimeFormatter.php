<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;
use Psr\Log\LoggerInterface;

class DateTimeFormatter extends AbstractLoggerAware implements BuilderAware {
	/** @var string */
	private $dateFmt;
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
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []) {
		$message = sprintf($this->format, date($this->dateFmt), $message);
		$this->logger()->log($level, $message, $context);
	}
}
