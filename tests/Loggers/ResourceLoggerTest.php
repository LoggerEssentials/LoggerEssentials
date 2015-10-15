<?php
namespace Logger\Loggers;

use Logger\Formatters\FormatFormatter;
use Logger\Tools\LogLevelTranslator;

class ResourceLoggerTest extends \PHPUnit_Framework_TestCase {
	public function testLogging() {
		$resource = fopen('php://memory', 'r+');
		$logger = new FormatFormatter(new ResourceLogger($resource), "%s\n");
		foreach(LogLevelTranslator::getLevelTokens() as $level) {
			$logger->log($level, $level);
		}
		rewind($resource);
		$data = fread($resource, 4096);
		$this->assertEquals("emergency\nalert\ncritical\nerror\nwarning\nnotice\ninfo\ndebug\n", $data);
	}
}
