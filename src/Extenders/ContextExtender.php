<?php
namespace Logger\Extenders;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class ContextExtender extends AbstractLoggerAware {
	/** @var array<string, mixed> */
	private $keyValueArray;

	/**
	 * @param LoggerInterface $logger
	 * @param array<string, mixed> $keyValueArray
	 */
	public static function wrap(LoggerInterface $logger, array $keyValueArray): self {
		return new self($logger, $keyValueArray);
	}

	/**
	 * @param LoggerInterface $logger
	 * @param array<string, mixed> $keyValueArray
	 */
	public function __construct(LoggerInterface $logger, array $keyValueArray) {
		parent::__construct($logger);
		$this->keyValueArray = $keyValueArray;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		foreach($this->keyValueArray as $key => $value) {
			if(is_object($value)) {
				if(method_exists($value, '__toString')) {
					$value = (string) $value;
				} else {
					$value = json_encode($value);
				}
			}
			$context[$key] = $value;
		}
		$this->logger()->log($level, $message, $context);
	}
}
