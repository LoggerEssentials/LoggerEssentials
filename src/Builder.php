<?php

namespace Logger;

use Psr\Log\LoggerInterface;

abstract class Builder {
	/**
	 * Example:
	 *
	 * $logger = Builder::chain(
	 *     ResourceLogger::outputToStdOut()
	 * 	   fn(LoggerInterface $logger) => new ContextJsonFormatter($logger),
	 * 	   fn(LoggerInterface $logger) => new StacktraceExtender($logger)
	 * );
	 *
	 * @param callable(LoggerInterface): LoggerInterface ...$chain
	 * @return LoggerInterface
	 */
	public static function chain(LoggerInterface $first, callable ...$chain): LoggerInterface {
		/** @var LoggerInterface $logger */
		$logger = array_reduce($chain, static fn(LoggerInterface $logger, callable $fn) => $fn($logger), $first); // @phpstan-ignore-line

		return $logger;
	}
}
