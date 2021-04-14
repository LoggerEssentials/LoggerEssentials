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
	public function __construct(string $concatenator = ' > ', string $endingConcatenator = ': ') {
		$this->concatenator = $concatenator;
		$this->endingConcatenator = $endingConcatenator;
	}

	/**
	 * @inheritDoc
	 */
	public function render(string $message, array $parents): string {
		$path = implode($this->concatenator, $parents);
		if($path) {
			$message = implode($this->endingConcatenator, [$path, $message]);
		}
		return $message;
	}
}
