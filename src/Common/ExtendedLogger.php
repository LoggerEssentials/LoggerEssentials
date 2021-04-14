<?php
namespace Logger\Common;

use Exception;
use Psr\Log\LoggerInterface;

interface ExtendedLogger extends LoggerInterface {
	/**
	 * @param string|string[] $captions
	 * @param array<string, mixed> $context
	 * @return ExtendedLogger
	 */
	public function createSubLogger($captions, array $context = []): ExtendedLogger;

	/**
	 * Starts a new logging context for a region. The $caption-parameter
	 * will be prepended to an log-message. The actual $context will be
	 * merged over the $context-parameter given to this method.
	 *
	 * @template T
	 * @param string|array<int, int|float|string> $caption
	 * @param array<string, mixed> $context
	 * @param callable(ExtendedLogger): T $fn
	 * @return T
	 * @throws Exception
	 */
	public function context($caption, array $context, callable $fn);

	/**
	 * Like @see self::context() but it will emit an
	 * @see \Psr\Log\LogLevel::INFO info-Message on
	 * region start and will additionaly emit the
	 * time needed in seconds when reaching the end.
	 *
	 * @template T
	 * @param string|array<int, int|float|string> $caption
	 * @param array<string, mixed> $context
	 * @param callable(ExtendedLogger): T $fn
	 * @return T
	 * @throws Exception
	 */
	public function measure($caption, array $context, callable $fn);
}
