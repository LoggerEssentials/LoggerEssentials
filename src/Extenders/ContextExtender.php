<?php
namespace Logger\Extenders;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class ContextExtender extends AbstractLoggerAware {
	/** @var array */
	private $keyValueArray;

	/**
	 * @param LoggerInterface $logger
	 * @param array $keyValueArray
	 */
	public function __construct(LoggerInterface $logger, array $keyValueArray) {
		parent::__construct($logger);
		$this->keyValueArray = $keyValueArray;
	}

	/**
	 * Logs with an arbitrary level.
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		foreach($this->keyValueArray as $key => $value) {
			if(is_object($value)) {
				if(method_exists($value, '__toString')) {
					$value = (string)$value;
				} else {
					$value = json_encode($value);
				}
			}
			$context[$key] = $value;
		}
		$this->logger()->log($level, $message, $context);
	}
}
