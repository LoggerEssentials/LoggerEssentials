<?php
namespace Logger\Common;

use Exception;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerContextExtender;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerMessageRenderer;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerStandardContextExtender;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerStandardMessageRenderer;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class ExtendedPsrLoggerWrapper extends AbstractLogger implements ExtendedLogger {
	/** @var LoggerInterface */
	private $logger = null;
	/** @var string[] */
	private $captions = array();
	/** @var ExtendedLoggerMessageRenderer */
	private $messageRenderer;
	/** @var ExtendedLoggerContextExtender */
	private $contextExtender;

	/**
	 * @param LoggerInterface $logger
	 * @param ExtendedLoggerMessageRenderer $messageRenderer
	 * @param ExtendedLoggerContextExtender $contextExtender
	 */
	public function __construct(LoggerInterface $logger, ExtendedLoggerMessageRenderer $messageRenderer = null, ExtendedLoggerContextExtender $contextExtender = null) {
		if($messageRenderer === null) {
			$messageRenderer = new ExtendedLoggerStandardMessageRenderer();
		}
		if($contextExtender === null) {
			$contextExtender = new ExtendedLoggerStandardContextExtender();
		}
		$this->logger = $logger;
		$this->messageRenderer = $messageRenderer;
		$this->contextExtender = $contextExtender;
	}

	/**
	 * @return LoggerInterface
	 */
	public function getLogger() {
		return $this->logger;
	}

	/**
	 * @param $caption
	 * @return $this
	 */
	public function addCaption($caption) {
		$caption = strtr($caption, array("\n" => ' ', "\r" => ' ', "\t" => ' '));
		$this->captions[] = $caption;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCaptions() {
		return $this->captions;
	}

	/**
	 * @return $this
	 */
	public function clearCaptions() {
		$this->captions = array();
		return $this;
	}

	/**
	 * @param string $caption
	 * @return $this
	 */
	public function createSubLogger($caption) {
		$logger = new static($this->logger, $this->messageRenderer);
		foreach($this->captions as $parentCaption) {
			$logger->addCaption($parentCaption);
		}
		$logger->addCaption($caption);
		return $logger;
	}

	/**
	 * @param string $caption
	 * @param array $context
	 * @param callable $fn
	 * @return $this
	 * @throws Exception
	 */
	public function context($caption, array $context = [], $fn) {
		try {
			$this->captions[] = $caption;
			$result = call_user_func($fn);
			array_pop($this->captions);
			return $result;
		} catch(Exception $e) {
			array_pop($this->captions);
			throw $e;
		}
	}

	/**
	 * Logs with an arbitrary level.
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$message = $this->messageRenderer->render($message, $this->captions);
		$context = $this->contextExtender->extend($context, $this->captions);
		$this->logger->log($level, $message, $context);
	}
}
