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
	/** @var array */
	private $captionTrail;
	/** @var array */
	private $context;

	/**
	 * @param LoggerInterface $logger
	 * @param string[] $captionTrail
	 * @param array $context
	 * @param ExtendedLoggerMessageRenderer $messageRenderer
	 * @param ExtendedLoggerContextExtender $contextExtender
	 */
	public function __construct(LoggerInterface $logger, array $captionTrail = array(), array $context = array(), ExtendedLoggerMessageRenderer $messageRenderer = null, ExtendedLoggerContextExtender $contextExtender = null) {
		if($messageRenderer === null) {
			$messageRenderer = new ExtendedLoggerStandardMessageRenderer();
		}
		if($contextExtender === null) {
			$contextExtender = new ExtendedLoggerStandardContextExtender();
		}
		$this->logger = $logger;
		$this->messageRenderer = $messageRenderer;
		$this->contextExtender = $contextExtender;
		$this->captionTrail = $captionTrail;
		$this->context = $context;
	}

	/**
	 * @return LoggerInterface
	 */
	public function getLogger() {
		return $this->logger;
	}

	/**
	 * @return string
	 */
	public function getCaptions() {
		return $this->captions;
	}

	/**
	 * @param string $caption
	 * @param array $context
	 * @return $this
	 */
	public function createSubLogger($caption, array $context = array()) {
		$captions = $this->captions;
		$captions[] = $caption;
		$context = array_merge($this->context, $context);
		$logger = new static($this->logger, $captions, $context, $this->messageRenderer);
		return $logger;
	}

	/**
	 * @param string|array $captions
	 * @param array $context
	 * @param callable $fn
	 * @return $this
	 * @throws Exception
	 */
	public function context($captions, array $context = [], $fn) {
		try {
			if(!is_array($captions)) {
				$captions = [$captions];
			}
			foreach($captions as $caption) {
				$this->captions[] = $caption;
			}
			$result = call_user_func($fn);
			for($i = 0; $i<count($captions); $i++) {
				array_pop($this->captions);
			}
			return $result;
		} catch(Exception $e) {
			for($i = 0; $i<count($captions); $i++) {
				array_pop($this->captions);
			}
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
