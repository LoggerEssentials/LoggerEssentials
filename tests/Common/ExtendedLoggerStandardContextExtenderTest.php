<?php
namespace Logger\Common;

use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerStandardContextExtender;
use PHPUnit\Framework\TestCase;

class ExtendedLoggerStandardContextExtenderTest extends TestCase {
	public function testExtendMergesAndOverrides(): void {
		$e = new ExtendedLoggerStandardContextExtender();
		$r = $e->extend(['a' => 1, 'b' => 2], ['b' => 3, 'c' => 4]);
		self::assertSame(['a' => 1, 'b' => 3, 'c' => 4], $r);
	}
}

