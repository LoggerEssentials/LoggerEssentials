<?php
namespace Logger\Common;

use Logger\Common\ExtendedPsrLoggerWrapper\CapturedLogEvent;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerCaptionTrail;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerContextExtender;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerMessageRenderer;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerStandardContextExtender;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerStandardMessageRenderer;
use Logger\Loggers\CallbackLogger;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class ExtendedLoggerImpl extends AbstractLogger implements ExtendedLogger {
	/** @var LoggerInterface */
	private $logger;
	/** @var ExtendedLoggerCaptionTrail */
	private $captionTrail;
	/** @var ExtendedLoggerMessageRenderer */
	private $messageRenderer;
	/** @var ExtendedLoggerContextExtender */
	private $contextExtender;
	/** @var array */
	private $context;

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
	 * @return string[]
	 */
	public function getCaptionTrail() {
		return $this->captionTrail->getCaptions();
	}

	/**
	 * @inheritDoc
	 */
	public function createSubLogger($captions, array $context = []) {
		if(!is_array($captions)) {
			$captions = [$captions];
		}
		$captionTrail = new ExtendedLoggerCaptionTrail($this->captionTrail);
		foreach($captions as $caption) {
			$captionTrail->addCaption($caption);
		}
		$context = $this->contextExtender->extend($this->context, $context);
		return new static($this->logger, $captionTrail, $context, $this->messageRenderer);
	}

	/**
	 * @inheritDoc
	 */
	public function context($captions, array $context = [], $fn) {
		if(!is_array($captions)) {
			$captions = [$captions];
		}
		$oldContext = $this->context;
		$this->context = $this->contextExtender->extend($this->context, $context);
		$coupon = $this->captionTrail->addCaption($captions);
		try {
			return $fn($this);
		} finally {
			$this->captionTrail->removeCaption($coupon);
			$this->context = $oldContext;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function measure($captions, array $context = [], $fn) {
		return $this->context($captions, $context, function ($logger) use ($fn) {
			$this->info("Enter context");
			$timer1 = microtime(true);
			$fn($logger);
			$timer2 = microtime(true);
			$time = $timer2 - $timer1;
			$this->info("Exit context: {$time} seconds", ['context-time' => ['start' => $timer1, 'end' => $timer2, 'time' => $time]]);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function intercept($fn, $callback) {
		$previousLogger = $this->logger;
		$this->logger = new CallbackLogger(static function ($level, $message, $context) use ($previousLogger, $callback) {
			$capturedLogEvent = new CapturedLogEvent($level, $message, $context, $previousLogger);
			$callback($capturedLogEvent);
		});
		try {
			return $fn($this);
		} finally {
			$this->logger = $previousLogger;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []) {
		$captions = $this->captionTrail->getCaptions();
		$message = $this->messageRenderer->render($message, $captions);
		$context = $this->contextExtender->extend($this->context, $context);
		$this->logger->log($level, $message, $context);
	}
}
