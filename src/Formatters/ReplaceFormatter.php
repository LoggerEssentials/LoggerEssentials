<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class ReplaceFormatter extends AbstractLoggerAware {
	/** @var array */
	private $replacement;

	/**
	 * @param LoggerInterface $logger
	 * @param array $replacement
	 */
	public function __construct(LoggerInterface $logger, array $replacement) {
		parent::__construct($logger);
		$this->replacement = $replacement;
	}

	/**
	 * Logs with an arbitrary level.
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$message = strtr($message, $this->replacement);
		$this->logger()->log($level, $message, $context);
	}
}
