<?php
namespace Logger\Loggers;

use Logger\Formatters\FormatFormatter;
use Logger\Tools\LogLevelTranslator;
use PHPUnit\Framework\TestCase;

class ResourceLoggerTest extends TestCase {
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
