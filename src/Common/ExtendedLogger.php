<?php
namespace Logger\Common;

use Psr\Log\LoggerInterface;

interface ExtendedLogger extends LoggerInterface {
	/**
	 * @param string $captions
	 * @return ExtendedLogger
	 */
	public function createSubLogger($captions);

	/**
	 * @param string $caption
	 * @param array $context
	 * @param callable $fn
	 * @return ExtendedLogger
	 */
	public function context($caption, array $context = array(), $fn);
}
