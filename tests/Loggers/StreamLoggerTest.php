<?php
namespace Logger\Loggers;

use Logger\Formatters\FormatFormatter;
use Logger\Tools\LogLevelTranslator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class StreamLoggerTest extends TestCase {
	public function testLogging(): void {
		$resource = fopen('php://memory', 'rb+');
		if($resource === false) {
			throw new RuntimeException();
		}
		$logger = new FormatFormatter(new ResourceLogger($resource), "%s\n");
		foreach(LogLevelTranslator::getLevelTokens() as $level) {
			$logger->log($level, $level);
		}
		rewind($resource);
		$data = fread($resource, 4096);
		self::assertEquals("emergency\nalert\ncritical\nerror\nwarning\nnotice\ninfo\ndebug\n", $data);
	}
}
