<?php
namespace Logger\Extenders;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class ContextExtender extends AbstractLoggerAware {
	/** @var array<string, mixed> */
	private $keyValueArray;

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
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []) {
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
