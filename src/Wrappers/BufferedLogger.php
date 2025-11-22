<?php
namespace Logger\Wrappers;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class BufferedLogger extends AbstractLogger {
	/** @var LoggerInterface */
	private $logger;
	/** @var list<array{string, string, array<string, mixed>}> */
	private $entries = [];
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
	 */
	public function flush(): self {
		// This is not optimal, but due to the fact that loggers COULD throw an exception for some reason, we need to
		// get rid of those entries already completed.
		while(count($this->entries)) {
			[$level, $message, $context] = array_shift($this->entries);
			$this->logger->log($level, $message, $context);
		}
		return $this;
	}

	/**
	 * @return array<int, array{string, string, array<string, mixed>}>
	 */
	public function getBuffer(): array {
		return $this->entries;
	}

	/**
	 * @param array<int, array{string, string, array<string, mixed>}> $entries
	 * @return $this
	 */
	public function setBuffer(array $entries): self {
		foreach($entries as [$level, $message, $context]) {
			$this->log($level, $message, $context);
		}
		return $this;
	}

	/**
	 * @return $this
	 */
	public function clearBuffer(): self {
		$this->entries = [];
		return $this;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$this->entries[] = [$level, $message, $context];
		if($this->maxEntries > -1 && count($this->entries) >= $this->maxEntries) {
			$this->flush();
		}
	}
}
