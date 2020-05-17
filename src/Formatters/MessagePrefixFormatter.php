<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

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
	public function __construct(LoggerInterface $logger, $caption = null, $concatenator = ' > ', $endingConcatenator = ': ') {
		parent::__construct($logger);
		$this->caption = $caption;
		$this->concatenator = $concatenator;
		$this->endingConcatenator = $endingConcatenator;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = array()) {
		$parts = array();
		if(is_array($this->caption)) {
			$parts[] = implode($this->concatenator, $this->caption);
		} elseif(is_scalar($this->caption)) {
			/** @var mixed $caption */
			$caption = $this->caption;
			$parts[] = (string) $caption;
		}
		$parts[] = $message;
		$parts = array_filter($parts);
		$newMessage = implode($this->endingConcatenator, $parts);
		$this->logger()->log($level, $newMessage, $context);
	}
}
