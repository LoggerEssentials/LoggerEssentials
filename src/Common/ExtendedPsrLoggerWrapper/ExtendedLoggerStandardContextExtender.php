<?php
namespace Logger\Common\ExtendedPsrLoggerWrapper;

class ExtendedLoggerStandardContextExtender implements ExtendedLoggerContextExtender {
	/**
	 * @param array $context
	 * @param array $parents
	 * @return array
	 */
	public function extend(array $context, array $parents) {
		return $context;
	}
}
