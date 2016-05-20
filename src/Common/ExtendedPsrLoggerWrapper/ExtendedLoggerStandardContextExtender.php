<?php
namespace Logger\Common\ExtendedPsrLoggerWrapper;

class ExtendedLoggerStandardContextExtender implements ExtendedLoggerContextExtender {
	/**
	 * @param array $parent
	 * @param array $context
	 * @return array
	 */
	public function extend(array $parent, array $context) {
		return array_merge($parent, $context);
	}
}
