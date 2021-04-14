<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class TemplateFormatterTest extends FormatterTestCase {
	public function testDate(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '[%|date:c%]');
		$formatter->log(LogLevel::DEBUG, '');
		self::assertRegExp('/\\[\\d{4}-\\d{2}-\\d{2}\\T\\d{2}:\\d{2}:\\d{2}\\+\\d{2}:\\d{2}\\]/', (string) $testLogger->getLastLine()->getMessage());
	}

	public function testNobr(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|nobr%');
		$formatter->log(LogLevel::DEBUG, "This\nis\ra\r\ntest");
		self::assertEquals('This is a test', $testLogger->getLastLine()->getMessage());
	}

	public function testTrim(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|trim%');
		$formatter->log(LogLevel::DEBUG, "   This is a test   ");
		self::assertEquals('This is a test', $testLogger->getLastLine()->getMessage());

		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|trim:"."%');
		$formatter->log(LogLevel::DEBUG, "...This is a test...");
		self::assertEquals('This is a test', $testLogger->getLastLine()->getMessage());
	}

	public function testLTrim(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|ltrim%');
		$formatter->log(LogLevel::DEBUG, "   This is a test   ");
		self::assertEquals('This is a test   ', $testLogger->getLastLine()->getMessage());

		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|ltrim:"."%');
		$formatter->log(LogLevel::DEBUG, "...This is a test...");
		self::assertEquals('This is a test...', $testLogger->getLastLine()->getMessage());
	}

	public function testRTrim(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|rtrim%');
		$formatter->log(LogLevel::DEBUG, "   This is a test   ");
		self::assertEquals('   This is a test', $testLogger->getLastLine()->getMessage());

		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|rtrim:"."%');
		$formatter->log(LogLevel::DEBUG, "...This is a test...");
		self::assertEquals('...This is a test', $testLogger->getLastLine()->getMessage());
	}

	public function testJson(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%context|json%');
		$formatter->log(LogLevel::DEBUG, "", array());
		self::assertEquals('{}', $testLogger->getLastLine()->getMessage());

		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%context|json%');
		$formatter->log(LogLevel::DEBUG, "", array('test' => 123));
		self::assertEquals('{"test":123}', $testLogger->getLastLine()->getMessage());
	}

	public function testPad(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|pad:8%');
		$formatter->log(LogLevel::DEBUG, "test");
		self::assertEquals('  test  ', $testLogger->getLastLine()->getMessage());
	}

	public function testLPad(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|lpad:8%');
		$formatter->log(LogLevel::DEBUG, "test");
		self::assertEquals('test    ', $testLogger->getLastLine()->getMessage());
	}

	public function testRPad(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|rpad:8%');
		$formatter->log(LogLevel::DEBUG, "test");
		self::assertEquals('    test', $testLogger->getLastLine()->getMessage());
	}

	public function testUppercase(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|uppercase%');
		$formatter->log(LogLevel::DEBUG, "test");
		self::assertEquals('TEST', $testLogger->getLastLine()->getMessage());
	}

	public function testLowercase(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|lowercase%');
		$formatter->log(LogLevel::DEBUG, "TEST");
		self::assertEquals('test', $testLogger->getLastLine()->getMessage());
	}

	public function testLCFirst(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|lcfirst%');
		$formatter->log(LogLevel::DEBUG, "THIS IS A TEST");
		self::assertEquals('tHIS IS A TEST', $testLogger->getLastLine()->getMessage());
	}

	public function testUCFirst(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|ucfirst%');
		$formatter->log(LogLevel::DEBUG, "this is a test");
		self::assertEquals('This is a test', $testLogger->getLastLine()->getMessage());
	}

	public function testUCWords(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|ucwords%');
		$formatter->log(LogLevel::DEBUG, "this is a test");
		self::assertEquals('This Is A Test', $testLogger->getLastLine()->getMessage());
	}

	public function testCut(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|cut:7%');
		$formatter->log(LogLevel::DEBUG, "this is a test");
		self::assertEquals('this is', $testLogger->getLastLine()->getMessage());
	}

	public function testDefault(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%notexisting|default:"Defaultvalue"%');
		$formatter->log(LogLevel::DEBUG, "");
		self::assertEquals('Defaultvalue', $testLogger->getLastLine()->getMessage());
	}

	public function testDefaultFormat(): void {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger);
		$formatter->log(LogLevel::DEBUG, 'This is a test');
		self::assertRegExp('/\\[\\d{4}-\\d{2}-\\d{2}\\T\\d{2}:\\d{2}:\\d{2}\\+\\d{2}:\\d{2}\\] [A-Z]+\\s+This is a test \\- \\{\\}/', (string) $testLogger->getLastLine()->getMessage());
	}
}
