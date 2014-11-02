<?php
namespace Logger\Common;

class FormatterTestCase extends \PHPUnit_Framework_TestCase {
	/**
	 * @return TestLogger
	 */
	protected function createTestLogger() {
		return new TestLogger();
	}
}