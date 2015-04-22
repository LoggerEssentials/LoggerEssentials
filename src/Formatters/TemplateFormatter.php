<?php
namespace Logger\Formatters;

use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;

class TemplateFormatter extends AbstractLoggerAware {
	const DEFAULT_FORMAT = "[%now|date:c%] %level|lpad:10|uppercase% %message|nobr% %ip|default:\"-\"% %context|json%\n";

	/** @var string */
	private $format;
	/** @var array */
	private $values;
	/** @var array */
	private $extra = null;

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
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$packet = array(
			'level' => $level,
			'message' => $message,
			'context' => $context,
			'now' => date('c')
		);
		$packet = array_merge($this->extra, $packet);
		$values = array();
		foreach($this->values as $valueDesc) {
			$key = $valueDesc[0];
			$value = array_key_exists($key, $packet) ? $packet[$key] : null;
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
		$values = array();
		$fn = function ($matches) use (&$values) {
			$values[] = $matches[1];
			return '%s';
		};
		$format = preg_replace_callback('/%([^%]+)%/', $fn, $format);
		$result = array();
		foreach($values as &$value) {
			$result[] = $this->extractConverters($value);
		}
		return array($format, $result);
	}

	/**
	 * @param string $value
	 * @return array
	 */
	private function extractConverters($value) {
		list($input, $modifiers) = explode('|', $value . '|', 2);
		$modifiers = rtrim($modifiers, '|');
		if(!$modifiers) {
			return array($input ?: null, function ($value) { return $value; });
		}
		$modifiers = explode('|', $modifiers);
		$modifiers = $this->expandModifiers($modifiers);
		return array($input ?: null, $modifiers);
	}

	/**
	 * @param string[] $modifiers
	 * @return \Closure[]
	 */
	private function expandModifiers($modifiers) {
		$functions = array();
		foreach($modifiers as $modifier) {
			list($command, $params) = $this->extractFn($modifier);
			$functions[] = $this->convertCommandToClosure($command, $params);
		}
		return function ($value) use ($functions) {
			foreach ($functions as $fn) {
				$value = call_user_func($fn, $value);
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
			if(substr($param, 0, 1) === '"' && substr($param, strlen($param) - 1, 1) === '"') {
				$param = substr($param, 1, strlen($param) - 2);
				$param = strtr($param, array('\\"' => '"', '\\\\' => '\\'));
			}
		}
		return array($command, $params);
	}

	/**
	 * @param string $command
	 * @param array $params
	 * @return \Closure
	 * @throws \Exception
	 */
	private function convertCommandToClosure($command, array $params) {
		$param = function ($key, $default = null) use ($params) {
			if(!array_key_exists($key, $params)) {
				if($default !== null) {
					return $default;
				} else {
					throw new \Exception("Missing parameter {$key}");
				}
			}
			return $params[$key];
		};
		switch (strtolower($command)) {
			case 'date':
				return function ($value) use ($param) {
					$dt = new \DateTime($value);
					return $dt->format($param(0));
				};
			case 'nobr':
				return function ($value) {
					return preg_replace('/[\\r\\n]+/', ' ', $value);
				};
			case 'trim':
				return function ($value) use ($param) {
					return trim($value, $param(0, " \t\n\r\0\x0B"));
				};
			case 'ltrim':
				return function ($value) use ($param) {
					return ltrim($value, $param(0, " \t\n\r\0\x0B"));
				};
			case 'rtrim':
				return function ($value) use ($param) {
					return rtrim($value, $param(0, " \t\n\r\0\x0B"));
				};
			case 'json':
				return function ($value) {
					if(!count($value)) {
						$value = new \stdClass();
					}
					return json_encode($value);
				};
			case 'pad':
				return function ($value) use ($param) {
					return str_pad($value, $param(0), $param(1, ' '), STR_PAD_BOTH);
				};
			case 'lpad':
				return function ($value) use ($param) {
					return str_pad($value, $param(0), $param(1, ' '), STR_PAD_RIGHT);
				};
			case 'rpad':
				return function ($value) use ($param) {
					return str_pad($value, $param(0), $param(1, ' '), STR_PAD_LEFT);
				};
			case 'uppercase':
				return function ($value) use ($param) {
					return strtoupper($value);
				};
			case 'lowercase':
				return function ($value) use ($param) {
					return strtolower($value);
				};
			case 'lcfirst':
				return function ($value) {
					return lcfirst($value);
				};
			case 'ucfirst':
				return function ($value) {
					return ucfirst($value);
				};
			case 'ucwords':
				return function ($value) {
					return ucwords($value);
				};
			case 'cut':
				return function ($value) use ($param) {
					return substr($value, 0, $param(0));
				};
			case 'default':
				return function ($value) use ($param) {
					return (string) $value === '' ? $param(0) : $value;
				};
		}
		throw new \Exception("Command not registered: {$command}");
	}
}