<?php
namespace Logger\Filters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class RegularExpressionFilter extends AbstractLoggerAware {
	private string $pattern;
	private string $modifiers;
	private bool $negate;

	/**
	 * @param LoggerInterface $logger
	 * @param string $pattern
	 * @param string $modifiers
	 * @param bool $negate
	 */
	public function __construct(LoggerInterface $logger, $pattern, $modifiers = 'u', bool $negate = false) {
		parent::__construct($logger);
		$this->pattern = $pattern;
		$this->modifiers = $modifiers;
		$this->negate = $negate;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = array()): void {
		$result = preg_match(sprintf("/%s/%s", preg_quote($this->pattern, '/'), $this->modifiers), $message);
		if(!$result !== $this->negate) {
			$this->logger()->log($level, $message, $context);
		}
	}
}
