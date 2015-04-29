<?php
namespace Logger\Common\ExtendedPsrLoggerWrapper;

interface ExtendedLoggerMessageRenderer {
	/**
	 * @param string $message
	 * @param array $parents
	 * @return string
	 */
	public function render($message, array $parents);
}
