<?php
namespace Logger\Common\ExtendedLogger;

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