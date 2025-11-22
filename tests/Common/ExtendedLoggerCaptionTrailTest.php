<?php
namespace Logger\Common;

use ArrayIterator;
use Logger\Common\ExtendedPsrLoggerWrapper\ExtendedLoggerCaptionTrail;
use PHPUnit\Framework\TestCase;

class ExtendedLoggerCaptionTrailTest extends TestCase {
	public function testAddAndGetCaptions(): void {
		$trail = new ExtendedLoggerCaptionTrail();
		$trail->addCaptions(['a', 'b']);
		self::assertSame(['a', 'b'], $trail->getCaptions());
	}

	public function testParentCaptionsAreIncluded(): void {
		$parent = new ExtendedLoggerCaptionTrail();
		$parent->addCaptions(['root']);
		$child = new ExtendedLoggerCaptionTrail($parent);
		$child->addCaptions(['leaf']);
		self::assertSame(['root', 'leaf'], $child->getCaptions());
	}

	public function testObjectsBecomeShortClassName(): void {
		$trail = new ExtendedLoggerCaptionTrail();
		$trail->addCaptions([new class(){}]);
		$captions = $trail->getCaptions();
		self::assertCount(1, $captions);
		self::assertSame('class@anonymous', substr($captions[0], 0, 15));
	}

	public function testRemoveCaption(): void {
		$trail = new ExtendedLoggerCaptionTrail();
		$c1 = $trail->addCaptions(['first']);
		$trail->addCaptions(['second']);
		$trail->removeCaption($c1);
		self::assertSame(['second'], $trail->getCaptions());
	}

	public function testIteratorAggregatesCaptions(): void {
		$trail = new ExtendedLoggerCaptionTrail();
		$trail->addCaptions(['x', 'y']);
		$it = $trail->getIterator();
		self::assertInstanceOf(ArrayIterator::class, $it);
		self::assertSame(['x', 'y'], iterator_to_array($it));
	}
}

