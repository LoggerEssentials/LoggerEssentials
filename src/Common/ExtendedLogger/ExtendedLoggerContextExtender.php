<?php
namespace Logger\Common\ExtendedLogger;

interface ExtendedLoggerContextExtender {
	/**
	 * @param array $context
	 * @param array $parents
	 * @return array
	 */
	public function extend(array $context, array $parents);
}