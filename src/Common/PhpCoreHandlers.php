<?php
namespace Logger\Common;

use Logger\Filters\LogLevelRangeFilter;
use Logger\Loggers\LoggerCollection;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class PhpCoreHandlers {
	/**
	 * @var array
	 */
	private static $phpErrorLevels = array(
		E_NOTICE => LogLevel::NOTICE,
		E_DEPRECATED => LogLevel::NOTICE,
		E_USER_DEPRECATED => LogLevel::NOTICE,
		E_WARNING => LogLevel::WARNING,
		E_STRICT => LogLevel::WARNING,
		E_USER_WARNING => LogLevel::WARNING,
		E_CORE_WARNING => LogLevel::WARNING,
		E_ERROR => LogLevel::ERROR,
		E_USER_ERROR => LogLevel::ERROR,
	);


	/**
	 */
	public static function enableExceptionsForErrors() {
		set_error_handler(function ($level, $message, $file, $line) {
			throw new \ErrorException($message, 0, $level, $file, $line);
		});
	}

	/**
	 * @param LoggerInterface $logger
	 * @param string $level
	 */
	public function registerAssertionHandler(LoggerInterface $logger, $level = LogLevel::WARNING) {
		static $errorLogger = null;
		if($errorLogger === null) {
			$errorLogger = new LoggerCollection();
			assert_options(ASSERT_ACTIVE, true);
			assert_options(ASSERT_WARNING, false);
			assert_options(ASSERT_CALLBACK, function ($file, $line, $message) use ($errorLogger, $level) {
				$errorLogger->log($level, $message, array(
					'file' => $file,
					'line' => $line
				));
			});
		}
		$errorLogger->add($logger);
	}

	/**
	 * @param LoggerInterface $logger
	 */
	public function registerFatalErrorHandler(LoggerInterface $logger) {
		static $errorLogger = null;
		if($errorLogger === null) {
			$errorLogger = new LoggerCollection();
			$errorLevels = self::$phpErrorLevels;
			register_shutdown_function(function () use ($errorLogger, $errorLevels) {
				$errorLogger = new LogLevelRangeFilter($errorLogger, LogLevel::ERROR);
				$error = error_get_last();
				if ($error !== null) {
					if(array_key_exists($error['type'], $errorLevels)) {
						$errorLevel = $errorLevels[$error['type']];
					} else {
						$errorLevel = LogLevel::CRITICAL;
					}
					$errorLogger->log($errorLevel, $error['message'], array(
						'file' => $error['file'],
						'line' => $error['line']
					));
				}
			});
		}
		$errorLogger->add($logger);
	}

	/**
	 * @param LoggerInterface $logger
	 */
	public static function registerExceptionHandler(LoggerInterface $logger) {
		static $errorLogger = null;
		if($errorLogger === null) {
			$errorLogger = new LoggerCollection();
			set_exception_handler(function (\Exception $exception) use ($errorLogger) {
				$errorLogger = new LogLevelRangeFilter($errorLogger, LogLevel::ERROR);
				$errorLogger->log(LogLevel::CRITICAL, $exception->getMessage(), array(
					'type' => get_class($exception),
					'code' => $exception->getCode(),
					'file' => $exception->getFile(),
					'line' => $exception->getLine(),
					'trace' => $exception->getTraceAsString(),
				));
			});
		}
		$errorLogger->add($logger);
	}
}