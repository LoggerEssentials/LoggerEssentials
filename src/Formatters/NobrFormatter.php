<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class NobrFormatter extends AbstractLoggerAware {
	/** @var string */
	private $replacement;

	/**
	 * @param LoggerInterface $logger
	 * @param string $replacement
	 */
	public function __construct(LoggerInterface $logger, $replacement = ' ') {
		parent::__construct($logger);
		$this->replacement = $replacement;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = array()) {
		$message = preg_replace("/[\r\n]+/", $this->replacement, $message);
		$this->logger()->log($level, $message, $context);
	}
}
