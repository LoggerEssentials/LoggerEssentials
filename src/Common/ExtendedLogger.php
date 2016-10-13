<?php
namespace Logger\Common;

use Exception;
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
	
	/**
	 * @param callable $fn
	 * @param callable $callback
	 * @return mixed
	 * @throws Exception
	 */
	public function intercept($fn, $callback);
}
