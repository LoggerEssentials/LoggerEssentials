<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class MessagePrefixFormatter extends AbstractLoggerAware {
	/** @var string|string[] */
	private $caption;
	/** @var string */
	private $concatenator;
	/** @var string */
	private $endingConcatenator;

	/**
	 * @param LoggerInterface $logger
	 * @param string|string[] $caption
	 * @param string $concatenator
	 * @param string $endingConcatenator
	 */
	public function __construct(LoggerInterface $logger, $caption, $concatenator = ' > ', $endingConcatenator = ': ') {
		parent::__construct($logger);
		$this->caption = $caption;
		$this->concatenator = $concatenator;
		$this->endingConcatenator = $endingConcatenator;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$parts = array();
		if(is_array($this->caption)) {
			$parts[] = implode($this->concatenator, $this->caption);
		} elseif(is_scalar($this->caption)) {
			$caption = $this->caption;
			$parts[] = (string) $caption;
		}
		$parts[] = $message;
		$parts = array_filter($parts);
		$newMessage = implode($this->endingConcatenator, $parts);
		$this->logger()->log($level, $newMessage, $context);
	}
}
