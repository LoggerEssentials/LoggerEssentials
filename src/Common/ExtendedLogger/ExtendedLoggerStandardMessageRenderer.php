<?php
namespace Logger\Common\ExtendedLogger;

class ExtendedLoggerStandardMessageRenderer implements ExtendedLoggerMessageRenderer {
	/** @var string */
	private $concatenator;
	/** @var string */
	private $endingConcatenator;

	/**
	 * ExtendedLoggerStandardMessageRenderer constructor.
	 *
	 * @param string $concatenator
	 * @param string $endingConcatenator
	 */
	public function __construct($concatenator = ' > ', $endingConcatenator = ': ') {
		$this->concatenator = $concatenator;
		$this->endingConcatenator = $endingConcatenator;
	}

	/**
	 * @param string $message
	 * @param array $parents
	 * @return string
	 */
	public function render($message, array $parents) {
		$path = join($this->concatenator, $parents);
		if($path) {
			$message = join($this->endingConcatenator, array($path, $message));
		}
		return $message;
	}
}