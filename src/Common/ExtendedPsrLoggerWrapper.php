<?php
namespace Logger\Common;

use Logger\CaptionRenderer;
use Logger\ExtendedLogger;
use Logger\Formatters\MessagePrefixFormatter;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class ExtendedPsrLoggerWrapper extends AbstractLogger implements ExtendedLogger {
	/**
	 * @var LoggerInterface
	 */
	private $logger = null;

	/**
	 * @var string[]
	 */
	private $captionPath = null;
	/**
	 * @var string
	 */
	private $concatenator;
	/**
	 * @var string
	 */
	private $endingConcatenator;

	/**
	 * @param LoggerInterface $logger
	 * @param string $concatenator
	 * @param string $endingConcatenator
	 */
	public function __construct(LoggerInterface $logger, $concatenator = ' > ', $endingConcatenator = ': ') {
		$this->logger = $logger;
		$this->concatenator = $concatenator;
		$this->endingConcatenator = $endingConcatenator;
	}

	/**
	 * @param string $caption
	 * @return ExtendedLogger
	 */
	public function createSubLogger($caption) {
		return new static(new MessagePrefixFormatter($this->logger, $caption, $this->concatenator, $this->endingConcatenator), $this->concatenator, $this->endingConcatenator);
	}

	/**
	 * Logs with an arbitrary level.
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$this->logger->log($level, $message, $context);
	}
}