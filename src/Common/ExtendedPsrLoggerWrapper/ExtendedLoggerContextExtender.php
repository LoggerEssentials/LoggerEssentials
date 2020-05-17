<?php
namespace Logger\Common\ExtendedPsrLoggerWrapper;

interface ExtendedLoggerContextExtender {
	/**
	 * @param array $parent
	 * @param array $context
	 * @return array
	 */
	public function extend(array $parent, array $context): array;
}
