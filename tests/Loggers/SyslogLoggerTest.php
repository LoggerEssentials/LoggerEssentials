<?php
namespace Logger\Loggers;

use Psr\Log\LogLevel;
use PHPUnit\Framework\TestCase;

// Namespace-level stubs for openlog/syslog to capture calls
/** @noinspection PhpUnused */
function openlog($ident, $option, $facility) {
	SyslogSpy::$openlogCalls[] = [$ident, $option, $facility];
}

/** @noinspection PhpUnused */
function syslog($priority, $message) {
	SyslogSpy::$syslogCalls[] = [$priority, $message];
}

class SyslogSpy {
	/** @var array<int, array{0: mixed, 1: mixed, 2: mixed}> */
	public static array $openlogCalls = [];
	/** @var array<int, array{0: mixed, 1: mixed}> */
	public static array $syslogCalls = [];

	public static function reset(): void {
		self::$openlogCalls = [];
		self::$syslogCalls = [];
	}
}

class SyslogLoggerTest extends TestCase {
	protected function setUp(): void {
		SyslogSpy::reset();
	}

	public function testDefaultOptionsIncludePidAndMaybePerror(): void {
		$logger = new SyslogLogger('my-app');
		$logger->log(LogLevel::INFO, 'hello-info');
		self::assertNotEmpty(SyslogSpy::$openlogCalls);
		[$ident, $options, $facility] = SyslogSpy::$openlogCalls[0];
		self::assertSame('my-app', $ident);
		self::assertSame(LOG_USER, $facility);
		self::assertSame(LOG_PID, $options);

		SyslogSpy::reset();
		$logger->log(LogLevel::ERROR, 'hello-error');
		[$_ident, $optsError] = SyslogSpy::$openlogCalls[0];
		self::assertSame(LOG_PID | LOG_PERROR, $optsError);
	}

	public function testProvidedOptionsAreUsedAsIs(): void {
		$logger = new SyslogLogger('unit', LOG_CONS, LOG_LOCAL0);
		$logger->log(LogLevel::NOTICE, 'ping');
		[$ident, $options, $facility] = SyslogSpy::$openlogCalls[0];
		self::assertSame('unit', $ident);
		self::assertSame(LOG_CONS, $options);
		self::assertSame(LOG_LOCAL0, $facility);
	}

	public function testLevelMapping(): void {
		$logger = new SyslogLogger('mapper');
		$logger->log(LogLevel::CRITICAL, 'crit!');
		[$priority, $message] = SyslogSpy::$syslogCalls[0];
		self::assertSame(LOG_CRIT, $priority);
		self::assertSame('crit!', $message);
	}
}

