<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;
use Psr\Log\LoggerInterface;

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
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []) {
		if($this->maxLength < mb_strlen($message, $this->charset)) {
			$ellipses = iconv('UTF-8', $this->charset, $this->ellipsis);
			$message = mb_substr($message, 0, $this->maxLength - strlen($this->ellipsis), $this->charset);
			$message .= $ellipses;
		}
		$this->logger()->log($level, $message, $context);
	}
}
