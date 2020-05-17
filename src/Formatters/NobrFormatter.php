<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;
use Psr\Log\LoggerInterface;

class NobrFormatter extends AbstractLoggerAware implements BuilderAware {
	/** @var string */
	private $replacement;

	/**
	 * @return int
	 */
	public static function getWeight(): int {
		return 0;
	}

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
	public function log($level, $message, array $context = []) {
		$message = preg_replace("/[\r\n]+/", $this->replacement, $message);
		$this->logger()->log($level, $message, $context);
	}
}
