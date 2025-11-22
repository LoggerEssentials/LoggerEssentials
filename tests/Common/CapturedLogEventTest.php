<?php
namespace Logger\Common;

use Logger\Common\ExtendedPsrLoggerWrapper\CapturedLogEvent;
use PHPUnit\Framework\TestCase;

class CapturedLogEventTest extends TestCase {
	public function testGetters(): void {
		$parent = new TestLogger();
		$e = new CapturedLogEvent('info', 'hello', ['x' => 1], $parent);
		self::assertSame('info', $e->getLevel());
		self::assertSame('hello', $e->getMessage());
		self::assertSame(['x' => 1], $e->getContext());
		self::assertSame($parent, $e->getParentLogger());
	}
}

