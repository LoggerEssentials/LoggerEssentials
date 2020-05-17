<?php
namespace Logger\Common\ExtendedPsrLoggerWrapper;

class ExtendedLoggerStandardMessageRenderer implements ExtendedLoggerMessageRenderer {
	/** @var string */
	private $concatenator;
	/** @var string */
	private $endingConcatenator;

	/**
	 * @param string $concatenator
	 * @param string $endingConcatenator
	 */
	public function __construct($concatenator = ' > ', $endingConcatenator = ': ') {
		$this->concatenator = $concatenator;
		$this->endingConcatenator = $endingConcatenator;
	}

	/**
	 * @inheritDoc
	 */
	public function render($message, array $parents): string {
		$path = implode($this->concatenator, $parents);
		if($path) {
			$message = implode($this->endingConcatenator, [$path, $message]);
		}
		return $message;
	}
}
