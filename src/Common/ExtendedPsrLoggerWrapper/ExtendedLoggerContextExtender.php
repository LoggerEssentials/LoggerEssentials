<?php
namespace Logger\Common\ExtendedPsrLoggerWrapper;

interface ExtendedLoggerContextExtender {
	/**
	 * @param array<string, mixed> $parent
	 * @param array<string, mixed> $context
	 * @return array<string, mixed>
	 */
	public function extend(array $parent, array $context): array;
}
