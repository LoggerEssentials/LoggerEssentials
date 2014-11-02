<?php
namespace Logger;

interface CaptionRenderer {
	/**
	 * @param array $captions
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @return string
	 */
	public function renderCaptionPath(array $captions, $level, $message, array $context);
}