<?php
namespace Logger\Wrappers;

use Exception;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class BufferedLogger extends AbstractLogger {
	/** @var LoggerInterface */
	private $logger;
	/** @var array */
	private $entries = array();
	/** @var int */
	private $maxEntries;

	/**
	 * @param LoggerInterface $logger
	 * @param int $maxEntries -1 = No automatic flushing, you have to call the flush()-method; 0 = always; 1+ = automatic.
	 */
	public function __construct(LoggerInterface $logger, $maxEntries = -1) {
		$this->logger = $logger;
		$this->maxEntries = $maxEntries;
	}

	/**
	 * @return $this
	 * @throws Exception
	 */
	public function flush() {
		// This is not optimal, but due to the fact that loggers COULD throw an exception for some reason, we need to
		// get rid of those entries already completed.
		while(count($this->entries)) {
			list($level, $message, $context) = array_shift($this->entries);
			$this->logger->log($level, $message, $context);
		}
		return $this;
	}

	/**
	 * @return array[]
	 */
	public function getBuffer() {
		return $this->entries;
	}

	/**
	 * @param array $entries
	 * @return $this
	 */
	public function setBuffer(array $entries) {
		foreach($entries as $entry) {
			list($level, $message, $context) = $entry;
			$this->log($level, $message, $context);
		}
		return $this;
	}

	/**
	 * @return $this
	 */
	public function clearBuffer() {
		$this->entries = array();
		return $this;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$this->entries[] = array($level, $message, $context);
		if($this->maxEntries > -1 && count($this->entries) >= $this->maxEntries) {
			$this->flush();
		}
	}
}