<?php
namespace Logger\Common;

use Logger\Common\ExtendedLoggerImpl\ExtendedLoggerImplCtorInterface;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerCaptionTrail;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerContextExtender;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerMessageRenderer;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerStandardContextExtender;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerStandardMessageRenderer;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class ExtendedLoggerImpl extends AbstractLogger implements ExtendedLogger, ExtendedLoggerImplCtorInterface {
	private LoggerInterface $logger;
	private ExtendedLoggerCaptionTrail $captionTrail;
	private ExtendedLoggerMessageRenderer $messageRenderer;
	private ExtendedLoggerContextExtender $contextExtender;
	/** @var TLogContext */
	private array $context;

	/**
	 * @param LoggerInterface $logger
	 * @param ExtendedLoggerCaptionTrail|null $captionTrail
	 * @param TLogContext $context
	 * @param ExtendedLoggerMessageRenderer|null $messageRenderer
	 * @param ExtendedLoggerContextExtender|null $contextExtender
	 */
	public function __construct(LoggerInterface $logger, ?ExtendedLoggerCaptionTrail $captionTrail = null, array $context = [], ?ExtendedLoggerMessageRenderer $messageRenderer = null, ?ExtendedLoggerContextExtender $contextExtender = null) {
		$this->logger = $logger;
		$captionTrail = $captionTrail ?? new ExtendedLoggerCaptionTrail();
		$this->context = $context;
		$messageRenderer = $messageRenderer ?? new ExtendedLoggerStandardMessageRenderer();
		$contextExtender = $contextExtender ?? new ExtendedLoggerStandardContextExtender();
		$this->messageRenderer = $messageRenderer;
		$this->contextExtender = $contextExtender;
		$this->captionTrail = $captionTrail;
	}

	/**
	 * @return LoggerInterface
	 */
	public function getLogger(): LoggerInterface {
		return $this->logger;
	}

	/**
	 * @return string[]
	 */
	public function getCaptionTrail(): array {
		return $this->captionTrail->getCaptions();
	}

	/**
	 * @inheritDoc
	 */
	public function createSubLogger($captions, array $context = []): ExtendedLogger {
		if(!is_array($captions)) {
			$captions = [$captions];
		}
		$captionTrail = new ExtendedLoggerCaptionTrail($this->captionTrail);
		$captionTrail->addCaptions($captions);
		$context = $this->contextExtender->extend($this->context, $context);
		return new static($this->logger, $captionTrail, $context, $this->messageRenderer);
	}

	/**
	 * @inheritDoc
	 */
	public function context($caption, array $context, callable $fn) {
		if(!is_array($caption)) {
			$caption = [$caption];
		}
		$oldContext = $this->context;
		$this->context = $this->contextExtender->extend($this->context, $context);
		$coupon = $this->captionTrail->addCaptions($caption);
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
	public function measure($caption, array $context, callable $fn) {
		return $this->context($caption, $context, function ($logger) use ($fn) {
			$this->info("Enter context");
			$timer1 = microtime(true);
			try {
				return $fn($logger);
			} finally {
				$timer2 = microtime(true);
				$time = $timer2 - $timer1;
				$timeStr = rtrim(sprintf("Exit context: %s seconds", number_format($time, 10, '.', '')), '0');
				$this->info($timeStr, ['context-time' => ['start' => $timer1, 'end' => $timer2, 'time' => $time]]);
			}
		});
	}

	/**
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$captions = $this->captionTrail->getCaptions();
		$message = $this->messageRenderer->render($message, $captions);
		$context = $this->contextExtender->extend($this->context, $context);
		$this->logger->log($level, $message, $context);
	}
}
