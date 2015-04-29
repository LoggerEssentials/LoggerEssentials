<?php
namespace Logger\Common;

use Psr\Log\LoggerInterface;

interface ExtendedLogger extends LoggerInterface {
	/**
	 * @param string $caption
	 * @return ExtendedLogger
	 */
	public function createSubLogger($caption);
}
