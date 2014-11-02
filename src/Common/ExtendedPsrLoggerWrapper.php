<?php
namespace Logger\Common;

use Logger\CaptionRenderer;
use Logger\ExtendedLogger;
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
	 * @var CaptionRenderer
	 */
	private $captionRenderer = null;

	/**
	 * @param LoggerInterface $logger
	 * @param CaptionRenderer $captionRenderer
	 * @param array $captionPath
	 */
	public function __construct(LoggerInterface $logger, CaptionRenderer $captionRenderer, array $captionPath = array()) {
		$this->logger = $logger;
		$this->captionRenderer = $captionRenderer;
		$this->captionPath = $captionPath;
	}

	/**
	 * @param string $caption
	 * @return ExtendedLogger
	 */
	public function createSubLogger($caption) {
		return new static($this->logger, $this->captionRenderer, $this->captionPath + array($caption));
	}

	/**
	 * Logs with an arbitrary level.
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$message = $this->captionRenderer->renderCaptionPath($this->captionPath, $level, $message, $context);
		$this->logger->log($level, $message, $context);
	}
}