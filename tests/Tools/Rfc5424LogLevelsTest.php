<?php
namespace Logger\Tools;

use PHPUnit\Framework\TestCase;

class Rfc5424LogLevelsTest extends TestCase {
	public function testConstantsValues(): void {
		self::assertSame(0, Rfc5424LogLevels::EMERGENCY);
		self::assertSame(1, Rfc5424LogLevels::ALERT);
		self::assertSame(2, Rfc5424LogLevels::CRITICAL);
		self::assertSame(3, Rfc5424LogLevels::ERROR);
		self::assertSame(4, Rfc5424LogLevels::WARNING);
		self::assertSame(5, Rfc5424LogLevels::NOTICE);
		self::assertSame(6, Rfc5424LogLevels::INFO);
		self::assertSame(7, Rfc5424LogLevels::DEBUG);
	}
}

