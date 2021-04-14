<?php
namespace Logger\Common\ExtendedPsrLoggerWrapper;

interface ExtendedLoggerMessageRenderer {
	/**
	 * @param string $message
	 * @param array<int, string> $parents
	 * @return string
	 */
	public function render(string $message, array $parents): string;
}
