<?php
namespace Logger\Common;

use Exception;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerCaptionTrail;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerContextExtender;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerMessageRenderer;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerStandardContextExtender;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerStandardMessageRenderer;
use Logger\Tools\TryFinally;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class ExtendedPsrLoggerWrapper extends AbstractLogger implements ExtendedLogger {
	/** @var LoggerInterface */
	private $logger = null;
	/** @var string[] */
	private $captionTrail = null;
	/** @var ExtendedLoggerMessageRenderer */
	private $messageRenderer;
	/** @var ExtendedLoggerContextExtender */
	private $contextExtender;
	/** @var array */
	private $context = array();

	/**
	 * @param LoggerInterface $logger
	 * @param ExtendedLoggerCaptionTrail $captionTrail
	 * @param array $context
	 * @param ExtendedLoggerMessageRenderer $messageRenderer
	 * @param ExtendedLoggerContextExtender $contextExtender
	 */
	public function __construct(LoggerInterface $logger, ExtendedLoggerCaptionTrail $captionTrail = null, array $context = array(), ExtendedLoggerMessageRenderer $messageRenderer = null, ExtendedLoggerContextExtender $contextExtender = null) {
		if($captionTrail === null) {
			$captionTrail = new ExtendedLoggerCaptionTrail();
		}
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
	public function getCaptionTrail() {
		return $this->captionTrail->getCaptions();
	}

	/**
	 * @param string|string[] $captions
	 * @param array $context
	 * @return $this
	 */
	public function createSubLogger($captions, array $context = array()) {
		if(!is_array($captions)) {
			$captions = array($captions);
		}
		$captionTrail = new ExtendedLoggerCaptionTrail($this->captionTrail);
		foreach($captions as $caption) {
			$captionTrail->addCaption($caption);
		}
		$context = $this->contextExtender->extend($this->context, $context);
		$logger = new static($this->logger, $captionTrail, $context, $this->messageRenderer);
		return $logger;
	}

	/**
	 * @param string|array $captions
	 * @param array $context
	 * @param callable $fn
	 * @return $this
	 * @throws Exception
	 */
	public function context($captions, array $context = array(), $fn) {
		if(!is_array($captions)) {
			$captions = array($captions);
		}
		$oldContext = $this->context;
		$this->context = $this->contextExtender->extend($this->context, $context);
		$coupon = $this->captionTrail->addCaption($captions);
		try {
			$result = call_user_func($fn, $this);
			$this->captionTrail->removeCaption($coupon);
			$this->context = $oldContext;
			return $result;
		} catch(Exception $e) {
			$this->captionTrail->removeCaption($coupon);
			$this->context = $oldContext;
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
		$captions = $this->captionTrail->getCaptions();
		$message = $this->messageRenderer->render($message, $captions);
		$context = $this->contextExtender->extend($this->context, $context);
		$this->logger->log($level, $message, $context);
	}
}
