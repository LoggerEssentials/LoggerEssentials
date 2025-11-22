<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class MaxLengthFormatter extends AbstractLoggerAware implements BuilderAware {
	/** @var int */
	private $maxLength;
	/** @var string */
	private $charset;
	/** @var string */
	private $ellipsis;

	/**
	 * @return int
	 */
	public static function getWeight(): int {
		return 0;
	}

	public static function wrap(LoggerInterface $logger, int $maxLength, string $ellipsis = '...', string $charset = 'UTF-8'): self {
		return new self($logger, $maxLength, $ellipsis, $charset);
	}

	/**
	 * @param LoggerInterface $logger
	 * @param int $maxLength
	 * @param string $ellipsis
	 * @param string $charset
	 */
	public function __construct(LoggerInterface $logger, $maxLength, $ellipsis = '...', $charset = 'UTF-8') {
		parent::__construct($logger);
		$this->ellipsis = $ellipsis;
		$this->maxLength = $maxLength < 0 ? 0 : $maxLength;
		$this->charset = $charset;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		if($this->maxLength < mb_strlen($message, $this->charset)) {
			$ellipses = iconv('UTF-8', $this->charset, $this->ellipsis);
			$message = mb_substr($message, 0, $this->maxLength - strlen($this->ellipsis), $this->charset);
			$message .= $ellipses;
		}
		$this->logger()->log($level, $message, $context);
	}
}
