<?php
namespace Logger\Formatters;

use Closure;
use DateTime;
use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;
use RuntimeException;
use stdClass;

class TemplateFormatter extends AbstractLoggerAware {
	const DEFAULT_FORMAT = "[%now|date:c%] %level|lpad:10|uppercase% %message|nobr% %ip|default:\"-\"% %context|json%\n";

	/** @var string */
	private $format;
	/** @var array */
	private $values;
	/** @var array */
	private $extra;

	/**
	 * @param LoggerInterface $logger
	 * @param string $format
	 * @param array $extra
	 */
	public function __construct(LoggerInterface $logger, $format = self::DEFAULT_FORMAT, array $extra = array()) {
		parent::__construct($logger);
		list($this->format, $this->values) = $this->compileFormat($format);
		$this->extra = $extra;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []) {
		$packet = [
			'level' => $level,
			'message' => $message,
			'context' => $context,
			'now' => date('c')
		];
		$packet = array_merge($this->extra, $packet);
		$values = [];
		foreach($this->values as $valueDesc) {
			$key = $valueDesc[0];
			$value = $packet[$key] ?? null;
			$values[] = call_user_func($valueDesc[1], $value);
		}
		$message = vsprintf($this->format, $values);
		$this->logger()->log($level, $message, $context);
	}

	/**
	 * @param string $format
	 * @return array
	 */
	private function compileFormat($format) {
		$values = [];
		$fn = static function ($matches) use (&$values) {
			$values[] = $matches[1];
			return '%s';
		};
		$format = preg_replace_callback('/%([^%]+)%/', $fn, $format);
		$result = [];
		foreach($values as $value) {
			$result[] = $this->extractConverters($value);
		}
		return [$format, $result];
	}

	/**
	 * @param string $value
	 * @return array
	 */
	private function extractConverters($value) {
		list($input, $modifiers) = explode('|', $value . '|', 2);
		$modifiers = rtrim($modifiers, '|');
		if(!$modifiers) {
			return [
				$input ?: null,
				static function ($value) {
					return $value;
				}
			];
		}
		$modifiers = explode('|', $modifiers);
		$modifiers = $this->expandModifiers($modifiers);
		return [$input ?: null, $modifiers];
	}

	/**
	 * @param string[] $modifiers
	 * @return Closure
	 */
	private function expandModifiers($modifiers) {
		$functions = [];
		foreach($modifiers as $modifier) {
			list($command, $params) = $this->extractFn($modifier);
			$functions[] = $this->convertCommandToClosure($command, $params);
		}
		return static function ($value) use ($functions) {
			foreach($functions as $fn) {
				$value = $fn($value);
			}
			return $value;
		};
	}

	/**
	 * @param string $modifier
	 * @return array
	 */
	private function extractFn($modifier) {
		list($command, $params) = explode(':', $modifier . ':', 2);
		$params = explode(':', $params);
		array_pop($params);
		foreach($params as &$param) {
			if(strpos($param, '"') === 0 && $param[strlen($param) - 1] === '"') {
				$param = substr($param, 1, -1);
				$param = strtr($param, ['\\"' => '"', '\\\\' => '\\']);
			}
		}
		return array($command, $params);
	}

	/**
	 * @param string $command
	 * @param array $params
	 * @return Closure
	 */
	private function convertCommandToClosure($command, array $params) {
		$param = static function ($key, $default = null) use ($params) {
			if(!array_key_exists($key, $params)) {
				if($default !== null) {
					return $default;
				}
				throw new RuntimeException("Missing parameter {$key}");
			}
			return $params[$key];
		};
		switch(strtolower($command)) {
			case 'date':
				return static function ($value) use ($param) {
					$dt = new DateTime($value);
					return $dt->format($param(0));
				};
			case 'nobr':
				return static function ($value) {
					return preg_replace('/[\\r\\n]+/', ' ', $value);
				};
			case 'trim':
				return static function ($value) use ($param) {
					return trim($value, $param(0, " \t\n\r\0\x0B"));
				};
			case 'ltrim':
				return static function ($value) use ($param) {
					return ltrim($value, $param(0, " \t\n\r\0\x0B"));
				};
			case 'rtrim':
				return static function ($value) use ($param) {
					return rtrim($value, $param(0, " \t\n\r\0\x0B"));
				};
			case 'json':
				return static function ($value) {
					if(!count($value)) {
						$value = new stdClass();
					}
					return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
				};
			case 'pad':
				return static function ($value) use ($param) {
					return str_pad($value, $param(0), $param(1, ' '), STR_PAD_BOTH);
				};
			case 'lpad':
				return static function ($value) use ($param) {
					return str_pad($value, $param(0), $param(1, ' '), STR_PAD_RIGHT);
				};
			case 'rpad':
				return static function ($value) use ($param) {
					return str_pad($value, $param(0), $param(1, ' '), STR_PAD_LEFT);
				};
			case 'uppercase':
				return static function ($value) {
					return strtoupper($value);
				};
			case 'lowercase':
				return static function ($value) {
					return strtolower($value);
				};
			case 'lcfirst':
				return static function ($value) {
					return lcfirst($value);
				};
			case 'ucfirst':
				return static function ($value) {
					return ucfirst($value);
				};
			case 'ucwords':
				return static function ($value) {
					return ucwords($value);
				};
			case 'cut':
				return static function ($value) use ($param) {
					return substr($value, 0, $param(0));
				};
			case 'default':
				return static function ($value) use ($param) {
					return (string)$value === '' ? $param(0) : $value;
				};
		}
		throw new RuntimeException("Command not registered: {$command}");
	}
}
