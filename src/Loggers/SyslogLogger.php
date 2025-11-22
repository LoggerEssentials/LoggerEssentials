<?php
namespace Logger\Loggers;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class SyslogLogger extends AbstractLogger {
	private string $ident;
	private ?int $options;
	private int $facility;

	public static function wrap(string $ident, ?int $options = null, int $facility = LOG_USER): self {
		return new self($ident, $options, $facility);
	}

	/**
	 * @param string $ident
	 * @param int|null $options
	 * @param int $facility
	 */
	public function __construct(string $ident, ?int $options = null, int $facility = LOG_USER) {
		$this->ident = $ident;
		$this->options = $options;
		$this->facility = $facility;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
		$sysLogLevel = match ($level) {
			LogLevel::DEBUG => LOG_DEBUG,
			LogLevel::NOTICE => LOG_NOTICE,
			LogLevel::WARNING => LOG_WARNING,
			LogLevel::ERROR => LOG_ERR,
			LogLevel::CRITICAL => LOG_CRIT,
			LogLevel::ALERT => LOG_ALERT,
			LogLevel::EMERGENCY => LOG_EMERG,
			default => LOG_INFO
		};

		$options = $this->options;
		if($options === null) {
			$options = LOG_PID;
			if($sysLogLevel < 4) {
				$options |= LOG_PERROR;
			}
		}

		openlog($this->ident, $options, $this->facility);
		syslog($sysLogLevel, (string) $message);
		# closelog() => http://php.net/manual/de/function.openlog.php#112856
	}
}
