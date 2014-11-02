<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class MessagePrefixFormatter extends AbstractLoggerAware {
	/**
	 * @var string
	 */
	private $caption = null;
	/**
	 * @var string
	 */
	private $concatenator = null;
	/**
	 * @var string
	 */
	private $endingConcatenator = null;

	/**
	 * @param LoggerInterface $logger
	 * @param string $caption
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
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$parts = array();
		if(is_array($this->caption)) {
			$parts[] = join($this->concatenator, $this->caption);
		} else {
			$parts[] = (string) $this->caption;
		}
		$parts[] = $message;
		$parts = array_filter($parts);
		$newMessage = join($this->endingConcatenator, $parts);
		$this->logger()->log($level, $newMessage, $context);
	}
}