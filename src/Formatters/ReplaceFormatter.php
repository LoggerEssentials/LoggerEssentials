<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Logger\Common\Builder\BuilderAware;
use Psr\Log\LoggerInterface;

class ReplaceFormatter extends AbstractLoggerAware implements BuilderAware {
	/** @var array */
	private $replacement;

	/**
	 * @return int
	 */
	public static function getWeight(): int {
		return 0;
	}

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
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []) {
		$message = strtr($message, $this->replacement);
		$this->logger()->log($level, $message, $context);
	}
}
