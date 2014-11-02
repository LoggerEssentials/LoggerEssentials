<?php
namespace Tools;

class DateTimeFormatter {
	/**
	 * @var \DateTime
	 */
	private $dateTime = null;
	/**
	 * @var string
	 */
	private $format;

	/**
	 * @param string $format
	 * @param \DateTimeZone $dateTimeZone
	 */
	public function __construct($format, \DateTimeZone $dateTimeZone = null) {
		$this->format = $format;
		if($dateTimeZone === null) {
			$dateTimeZone = new \DateTimeZone(date_default_timezone_get());
		}
		$this->dateTime = new \DateTime($format, $dateTimeZone);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->dateTime->format($this->format);
	}
}