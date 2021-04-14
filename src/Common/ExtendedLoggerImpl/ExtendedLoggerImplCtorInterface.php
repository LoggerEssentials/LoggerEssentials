<?php
namespace Logger\Common\ExtendedLoggerImpl;

use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerCaptionTrail;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerContextExtender;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerMessageRenderer;
use Psr\Log\LoggerInterface;

interface ExtendedLoggerImplCtorInterface {
	/**
	 * @param LoggerInterface $logger
	 * @param ExtendedLoggerCaptionTrail|null $captionTrail
	 * @param array<string, mixed> $context
	 * @param ExtendedLoggerMessageRenderer|null $messageRenderer
	 * @param ExtendedLoggerContextExtender|null $contextExtender
	 */
	public function __construct(LoggerInterface $logger, ?ExtendedLoggerCaptionTrail $captionTrail = null, array $context = [], ?ExtendedLoggerMessageRenderer $messageRenderer = null, ?ExtendedLoggerContextExtender $contextExtender = null);
}
