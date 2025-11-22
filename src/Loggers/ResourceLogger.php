<?php
namespace Logger\Loggers;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\AbstractLogger;
use RuntimeException;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class ResourceLogger extends AbstractLogger {
	/** @var resource */
	private $resource;

	public static function outputToStdOut(): self {
		if(defined('STDOUT')) {
			return new self(STDOUT);
		}
		$resource = fopen('php://stdout', 'wb');
		if($resource === false) {
			throw new RuntimeException('Could not open STDOUT');
		}
		return new self($resource);
	}

	public static function outputToStdErr(): self {
		if(defined('STDERR')) {
			return new self(STDERR);
		}
		$resource = fopen('php://stderr', 'wb');
		if($resource === false) {
			throw new RuntimeException('Could not open STDOUT');
		}
		return new self($resource);
	}

	/**
	 * @param resource $resource
	 */
	public function __construct($resource) {
		$this->resource = $resource;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		fwrite($this->resource, $message);
	}
}
