<?php
namespace Logger\Filters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class RegularExpressionFilter extends AbstractLoggerAware {
	/** @var string */
	private $pattern;
	/** @var string */
	private $modifiers;
	/** @var bool */
	private $negate;

	/**
	 * @param LoggerInterface $logger
	 * @param string $pattern
	 * @param string $modifiers
	 * @param bool $negate
	 */
	public function __construct(LoggerInterface $logger, $pattern, $modifiers = 'u', $negate = false) {
		parent::__construct($logger);
		$this->pattern = $pattern;
		$this->modifiers = $modifiers;
		$this->negate = (bool) $negate;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = array()) {
		$result = preg_match(sprintf("/%s/%s", preg_quote($this->pattern, '/'), $this->modifiers), $message);
		if(!$result !== $this->negate) {
			$this->logger()->log($level, $message, $context);
		}
	}
}
