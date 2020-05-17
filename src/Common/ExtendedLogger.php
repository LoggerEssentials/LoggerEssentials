<?php
namespace Logger\Common;

use Exception;
use Psr\Log\LoggerInterface;

interface ExtendedLogger extends LoggerInterface {
	/**
	 * @deprecated Use @see self::context() instead.
	 * @param string|string[] $captions
	 * @return ExtendedLogger
	 */
	public function createSubLogger($captions);

	/**
	 * Starts a new logging context for a region. The $caption-parameter
	 * will be prepended to an log-message. The actual $context will be
	 * merged over the $context-parameter given to this method.
	 *
	 * @param string|string[] $caption
	 * @param array $context
	 * @param callable $fn
	 * @return mixed
	 */
	public function context($caption, array $context = [], $fn);

	/**
	 * Like @see self::context() but it will emit an
	 * @see \Psr\Log\LogLevel::INFO info-Message on
	 * region start and will additionaly emit the
	 * time needed in seconds when reaching the end.
	 *
	 * @param string|string[] $caption
	 * @param array $context
	 * @param mixed $fn
	 * @return mixed
	 * @throws Exception
	 */
	public function measure($caption, array $context = [], $fn);

	/**
	 * @deprecated
	 * @param callable $fn
	 * @param callable $callback
	 * @return mixed
	 * @throws Exception
	 */
	public function intercept($fn, $callback);
}
