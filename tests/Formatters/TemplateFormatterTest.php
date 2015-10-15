<?php
namespace Logger\Formatters;

use Logger\Common\FormatterTestCase;
use Psr\Log\LogLevel;

class TemplateFormatterTest extends FormatterTestCase {
	public function testDate() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '[%|date:c%]');
		$formatter->log(LogLevel::DEBUG, '');
		$this->assertRegExp('/\\[\\d{4}-\\d{2}-\\d{2}\\T\\d{2}:\\d{2}:\\d{2}\\+\\d{2}:\\d{2}\\]/', $testLogger->getLastLine()->getMessage());
	}

	public function testNobr() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|nobr%');
		$formatter->log(LogLevel::DEBUG, "This\nis\ra\r\ntest");
		$this->assertEquals('This is a test', $testLogger->getLastLine()->getMessage());
	}

	public function testTrim() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|trim%');
		$formatter->log(LogLevel::DEBUG, "   This is a test   ");
		$this->assertEquals('This is a test', $testLogger->getLastLine()->getMessage());

		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|trim:"."%');
		$formatter->log(LogLevel::DEBUG, "...This is a test...");
		$this->assertEquals('This is a test', $testLogger->getLastLine()->getMessage());
	}

	public function testLTrim() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|ltrim%');
		$formatter->log(LogLevel::DEBUG, "   This is a test   ");
		$this->assertEquals('This is a test   ', $testLogger->getLastLine()->getMessage());

		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|ltrim:"."%');
		$formatter->log(LogLevel::DEBUG, "...This is a test...");
		$this->assertEquals('This is a test...', $testLogger->getLastLine()->getMessage());
	}

	public function testRTrim() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|rtrim%');
		$formatter->log(LogLevel::DEBUG, "   This is a test   ");
		$this->assertEquals('   This is a test', $testLogger->getLastLine()->getMessage());

		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|rtrim:"."%');
		$formatter->log(LogLevel::DEBUG, "...This is a test...");
		$this->assertEquals('...This is a test', $testLogger->getLastLine()->getMessage());
	}

	public function testJson() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%context|json%');
		$formatter->log(LogLevel::DEBUG, "", array());
		$this->assertEquals('{}', $testLogger->getLastLine()->getMessage());

		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%context|json%');
		$formatter->log(LogLevel::DEBUG, "", array('test' => 123));
		$this->assertEquals('{"test":123}', $testLogger->getLastLine()->getMessage());
	}

	public function testPad() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|pad:8%');
		$formatter->log(LogLevel::DEBUG, "test");
		$this->assertEquals('  test  ', $testLogger->getLastLine()->getMessage());
	}

	public function testLPad() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|lpad:8%');
		$formatter->log(LogLevel::DEBUG, "test");
		$this->assertEquals('test    ', $testLogger->getLastLine()->getMessage());
	}

	public function testRPad() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|rpad:8%');
		$formatter->log(LogLevel::DEBUG, "test");
		$this->assertEquals('    test', $testLogger->getLastLine()->getMessage());
	}

	public function testUppercase() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|uppercase%');
		$formatter->log(LogLevel::DEBUG, "test");
		$this->assertEquals('TEST', $testLogger->getLastLine()->getMessage());
	}

	public function testLowercase() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|lowercase%');
		$formatter->log(LogLevel::DEBUG, "TEST");
		$this->assertEquals('test', $testLogger->getLastLine()->getMessage());
	}

	public function testLCFirst() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|lcfirst%');
		$formatter->log(LogLevel::DEBUG, "THIS IS A TEST");
		$this->assertEquals('tHIS IS A TEST', $testLogger->getLastLine()->getMessage());
	}

	public function testUCFirst() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|ucfirst%');
		$formatter->log(LogLevel::DEBUG, "this is a test");
		$this->assertEquals('This is a test', $testLogger->getLastLine()->getMessage());
	}

	public function testUCWords() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|ucwords%');
		$formatter->log(LogLevel::DEBUG, "this is a test");
		$this->assertEquals('This Is A Test', $testLogger->getLastLine()->getMessage());
	}

	public function testCut() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%message|cut:7%');
		$formatter->log(LogLevel::DEBUG, "this is a test");
		$this->assertEquals('this is', $testLogger->getLastLine()->getMessage());
	}

	public function testDefault() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger, '%notexisting|default:"Defaultvalue"%');
		$formatter->log(LogLevel::DEBUG, "");
		$this->assertEquals('Defaultvalue', $testLogger->getLastLine()->getMessage());
	}

	public function testDefaultFormat() {
		$testLogger = $this->createTestLogger();
		$formatter = new TemplateFormatter($testLogger);
		$formatter->log(LogLevel::DEBUG, 'This is a test');
		$this->assertRegExp('/\\[\\d{4}-\\d{2}-\\d{2}\\T\\d{2}:\\d{2}:\\d{2}\\+\\d{2}:\\d{2}\\] [A-Z]+\\s+This is a test \\- \\{\\}/', $testLogger->getLastLine()->getMessage());
	}
}
