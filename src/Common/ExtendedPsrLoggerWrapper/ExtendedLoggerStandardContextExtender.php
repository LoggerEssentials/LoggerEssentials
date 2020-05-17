<?php
namespace Logger\Common\ExtendedPsrLoggerWrapper;

class ExtendedLoggerStandardContextExtender implements ExtendedLoggerContextExtender {
	/**
	 * @inheritDoc
	 */
	public function extend(array $parent, array $context): array {
		return array_merge($parent, $context);
	}
}
