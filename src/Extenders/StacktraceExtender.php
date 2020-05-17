<?php
namespace Logger\Extenders;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class StacktraceExtender extends AbstractLoggerAware {
	/** @var string */
	private $contextKey;
	/** @var int|null */
	private $debugbacktraceArgs;

	/**
	 * @param LoggerInterface $logger
	 * @param string $contextKey
	 * @param int $debugbacktraceArgs
	 */
	public function __construct(LoggerInterface $logger, $contextKey = 'stacktrace', $debugbacktraceArgs = null) {
		parent::__construct($logger);

		if($debugbacktraceArgs === null) {
			$debugbacktraceArgs = DEBUG_BACKTRACE_IGNORE_ARGS;
		}

		$this->contextKey = $contextKey;
		$this->debugbacktraceArgs = $debugbacktraceArgs;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = array()) {
		$stacktrace = debug_backtrace($this->debugbacktraceArgs);
		$context[$this->contextKey] = json_decode(json_encode($stacktrace), true);
		$this->logger()->log($level, $message, $context);
	}
}
