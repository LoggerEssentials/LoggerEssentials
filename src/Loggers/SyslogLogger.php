<?php
namespace Logger\Loggers;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class SyslogLogger extends AbstractLogger implements LoggerInterface {
	/** @var array */
	private static $levels = array(
		LogLevel::DEBUG => LOG_DEBUG,
		LogLevel::INFO => LOG_INFO,
		LogLevel::NOTICE => LOG_NOTICE,
		LogLevel::WARNING => LOG_WARNING,
		LogLevel::ERROR => LOG_ERR,
		LogLevel::CRITICAL => LOG_CRIT,
		LogLevel::ALERT => LOG_ALERT,
		LogLevel::EMERGENCY => LOG_EMERG,
	);
	/** @var string */
	private $ident;
	/** @var int|null */
	private $options = null;
	/** @var int */
	private $facility;

	/**
	 * @param string $ident
	 * @param int $options
	 * @param int $facility
	 */
	public function __construct($ident, $options = null, $facility = LOG_USER) {
		$this->ident = $ident;
		$this->options = $options;
		$this->facility = $facility;
	}

	/**
	 * Logs with an arbitrary level.
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$options = $this->options;
		if($options === null) {
			$options = LOG_PID;
			if(self::$levels[$level] < 4) {
				$options |= LOG_PERROR;
			}
		}
		openlog($this->ident, $options, $this->facility);
		syslog($level, $message);
		# closelog() => http://php.net/manual/de/function.openlog.php#112856
	}
}
