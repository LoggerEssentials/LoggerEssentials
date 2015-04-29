<?php
namespace Logger\Tools;

/**
 * Logging levels from syslog protocol defined in RFC 5424
 */
interface Rfc5424LogLevels {
	const EMERGENCY = 0;
	const ALERT = 1;
	const CRITICAL = 2;
	const ERROR = 3;
	const WARNING = 4;
	const NOTICE = 5;
	const INFO = 6;
	const DEBUG = 7;
}
