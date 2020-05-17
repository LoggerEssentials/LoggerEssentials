<?php
namespace Logger\Common;

use Logger\Common\Builder\BuilderAware;

class Builder {
	public static function build(BuilderAware ...$layers): ExtendedLogger {
	}
}
