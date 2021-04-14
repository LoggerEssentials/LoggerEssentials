<?php
namespace Logger\Common;

use PHPUnit\Framework\TestCase;

class FormatterTestCase extends TestCase {
	/**
	 * @return TestLogger
	 */
	protected function createTestLogger(): TestLogger{
		return new TestLogger();
	}
}
