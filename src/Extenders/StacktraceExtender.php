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
	public function log($level, $message, array $context = []): void {
		if($this->debugbacktraceArgs !== null) {
			$stacktrace = debug_backtrace($this->debugbacktraceArgs);
		} else {
			$stacktrace = debug_backtrace();
		}
		$enc = json_encode($stacktrace, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		$context[$this->contextKey] = json_decode($enc === false ? '{}' : $enc, true);
		$this->logger()->log($level, $message, $context);
	}
}
