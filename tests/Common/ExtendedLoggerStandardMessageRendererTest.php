<?php
namespace Logger\Common;

use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerStandardMessageRenderer;
use PHPUnit\Framework\TestCase;

class ExtendedLoggerStandardMessageRendererTest extends TestCase {
	public function testRendersWithParents(): void {
		$r = new ExtendedLoggerStandardMessageRenderer();
		$out = $r->render('Hello', ['a', 'b']);
		self::assertSame('a > b: Hello', $out);
	}

	public function testRendersWithoutParents(): void {
		$r = new ExtendedLoggerStandardMessageRenderer();
		$out = $r->render('Hello', []);
		self::assertSame('Hello', $out);
	}

	public function testCustomConcatenators(): void {
		$r = new ExtendedLoggerStandardMessageRenderer(' / ', ' => ');
		$out = $r->render('X', ['p', 'q']);
		self::assertSame('p / q => X', $out);
	}
}

