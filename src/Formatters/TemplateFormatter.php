<?php
namespace Logger\Formatters;

use DateTime;
use Logger\Common\AbstractLoggerAware;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;

/**
 * @phpstan-import-type TLogLevel from AbstractLoggerAware
 * @phpstan-import-type TLogMessage from AbstractLoggerAware
 * @phpstan-import-type TLogContext from AbstractLoggerAware
 */
class TemplateFormatter extends AbstractLoggerAware {
	public const DEFAULT_FORMAT = "[%now|date:c%] %level|lpad:10|uppercase% %message|nobr% %ip|default:\"-\"% %context|json%\n";

	private string $format;
	/** @var array<int, array{null|string, callable(string): string}> */
	private array $values;
	/** @var array<string, mixed> */
	private array $extra;

	/**
	 * @param LoggerInterface $logger
	 * @param string $format
	 * @param array<string, mixed> $extra
	 */
	public function __construct(LoggerInterface $logger, $format = self::DEFAULT_FORMAT, array $extra = []) {
		parent::__construct($logger);
		[$this->format, $this->values] = $this->compileFormat($format);
		$this->extra = $extra;
	}

	/**
	 * @param Throwable $exception
	 * @return array{class: class-string<Throwable>, message: string, code: int, file: string, line: int, stacktrace: string, previous?: array{class: class-string, message: string, code: int, file: string, line: int, stacktrace: string, previous?: array{}}}
	 */
	private static function exceptionToArray(Throwable $exception): array {
		$data = [
			'class'      => get_class($exception),
			'message'    => $exception->getMessage(),
			'code'       => (int) $exception->getCode(),
			'file'       => $exception->getFile(),
			'line'       => $exception->getLine(),
			'stacktrace' => $exception->getTraceAsString(),
		];

		if($exception->getPrevious() !== null) {
			$data['previous'] = self::exceptionToArray($exception->getPrevious());
		}

		/** @var array{class: class-string<Throwable>, message: string, code: int, file: string, line: int, stacktrace: string, previous?: array{class: class-string<Throwable>, message: string, code: int, file: string, line: int, stacktrace: string}} $data */
		return $data;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param TLogLevel $level
	 * @param TLogMessage $message
	 * @param TLogContext $context
	 */
	public function log($level, $message, array $context = []): void {
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

			/** @var string $value */
			$value = $packet[(string) $key] ?? null;
			$callable = $valueDesc[1];
			$values[] = call_user_func($callable, $value);
		}
		$message = vsprintf($this->format, $values);
		$this->logger()->log($level, $message, $context);
	}

	/**
	 * @param string $format
	 * @return array{string, array<int, array{null|string, callable(string): string}>}
	 */
	private function compileFormat(string $format): array {
		$values = [];
		$fn = static function (array $matches) use (&$values) {
			$values[] = $matches[1];
			return '%s';
		};
		$format = (string) preg_replace_callback('{%([^%]+)%}', $fn, $format);
		$result = [];
		/** @var string[] $values */
		foreach($values as $value) {
			$result[] = $this->extractConverters($value);
		}
		return [$format, $result];
	}

	/**
	 * @param string $value
	 * @return array{null|string, callable(string): string}
	 */
	private function extractConverters(string $value): array {
		[$input, $modifiers] = explode('|', $value . '|', 2);
		$modifiers = rtrim($modifiers, '|');
		if(!$modifiers) {
			return [$input ?: null, static fn (?string $value): string => (string) $value];
		}
		$modifiers = explode('|', $modifiers);
		$modifierFn = $this->expandModifiers($modifiers);
		return [$input ?: null, $modifierFn];
	}

	/**
	 * @param array<int, string> $modifiers
	 * @return callable(string): string
	 */
	private function expandModifiers(array $modifiers): callable {
		$functions = [];
		foreach($modifiers as $modifier) {
			[$command, $params] = $this->extractFn($modifier);
			$functions[] = $this->convertCommandToClosure($command, $params);
		}

		return static function (null|string|array $value) use ($functions): string {
			foreach($functions as $fn) {
				/** @var null|string|array<array-key, null|scalar> $val */
				$val = $value;
				$value = $fn($val);
			}
			if(is_string($value)) {
				return $value;
			}
			return '';
		};
	}

	/**
	 * @param string $modifier
	 * @return array{string, array<int, string>}
	 */
	private function extractFn(string $modifier): array {
		[$command, $params] = explode(':', "{$modifier}:", 2);
		$params = explode(':', $params);
		array_pop($params);
		foreach($params as &$param) {
			if(strpos($param, '"') === 0 && $param[strlen($param) - 1] === '"') {
				$param = substr($param, 1, -1);
				$param = strtr($param, ['\\"' => '"', '\\\\' => '\\']);
			}
		}
		return [$command, $params];
	}

	/**
	 * @param string $command
	 * @param array<int|string, string> $params
	 * @return callable(null|string|array<array-key, null|scalar>): string
	 */
	private function convertCommandToClosure(string $command, array $params): callable {
		$param = static function (null|int|string $key, null|string $default = null) use ($params): string {
			if(!array_key_exists($key ?? '', $params)) {
				if($default !== null) {
					return $default;
				}
				throw new RuntimeException("Missing parameter {$key}");
			}
			return $params[(string) $key];
		};
		switch(strtolower($command)) {
			case 'date':
				return static function (null|string|array $value) use ($param) {
					$value = is_array($value) ? null : $value;
					$dt = new DateTime($value ?? 'now');
					return $dt->format($param('0'));
				};
			case 'nobr':
				return static function (null|string|array $value): string {
					$value = is_array($value) ? null : $value;
					return (string) preg_replace('{[\\r\\n]+}', ' ', (string) $value);
				};
			case 'trim':
				return static function (null|string|array $value) use ($param): string {
					$value = is_array($value) ? null : $value;
					return trim((string) $value, $param(0, " \t\n\r\0\x0B"));
				};
			case 'ltrim':
				return static function (null|string|array $value) use ($param): string {
					$value = is_array($value) ? null : $value;
					return ltrim((string) $value, $param(0, " \t\n\r\0\x0B"));
				};
			case 'rtrim':
				return static function (null|string|array $value) use ($param): string {
					$value = is_array($value) ? null : $value;
					return rtrim((string) $value, $param(0, " \t\n\r\0\x0B"));
				};
			case 'json':
				return static function ($value): string {
					$value = (array) $value;
					if(array_key_exists('exception', $value) && $value['exception'] instanceof Throwable) {
						$value['exception'] = self::exceptionToArray($value['exception']);
					}
					return (string) json_encode((object) $value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
				};
			case 'pad':
				return static function (null|string|array $value) use ($param): string {
					$value = is_array($value) ? null : $value;
					return (string) str_pad((string) $value, (int) $param(0), $param(1, ' '), STR_PAD_BOTH);
				};
			case 'lpad':
				return static function (null|string|array $value) use ($param): string {
					$value = is_array($value) ? null : $value;
					return (string) str_pad((string) $value, (int) $param(0), $param(1, ' '), STR_PAD_RIGHT);
				};
			case 'rpad':
				return static function (null|string|array $value) use ($param): string {
					$value = is_array($value) ? null : $value;
					return (string) str_pad((string) $value, (int) $param(0), $param(1, ' '), STR_PAD_LEFT);
				};
			case 'uppercase':
				return static function (null|string|array $value): string {
					$value = is_array($value) ? null : $value;
					return (string) strtoupper((string) $value);
				};
			case 'lowercase':
				return static function (null|string|array $value): string {
					$value = is_array($value) ? null : $value;
					return (string) strtolower((string) $value);
				};
			case 'lcfirst':
				return static function (null|string|array $value): string {
					$value = is_array($value) ? null : $value;
					return (string) lcfirst((string) $value);
				};
			case 'ucfirst':
				return static function (null|string|array $value): string {
					$value = is_array($value) ? null : $value;
					return (string) ucfirst((string) $value);
				};
			case 'ucwords':
				return static function (null|string|array $value): string {
					$value = is_array($value) ? null : $value;
					return (string) ucwords((string) $value);
				};
			case 'cut':
				return static function (null|string|array $value) use ($param): string {
					$value = is_array($value) ? null : $value;
					return (string) substr((string) $value, 0, (int) $param(0));
				};
			case 'default':
				return static function (null|string|array $value) use ($param): string {
					$value = is_array($value) ? null : $value;
					return (string) $value === '' ? (string) $param(0) : (string) $value;
				};
		}
		throw new RuntimeException("Command not registered: {$command}");
	}
}
