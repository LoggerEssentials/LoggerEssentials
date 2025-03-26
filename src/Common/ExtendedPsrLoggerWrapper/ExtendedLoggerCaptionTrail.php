<?php
namespace Logger\Common\ExtendedPsrLoggerWrapper;

use ArrayIterator;
use IteratorAggregate;
use ReflectionClass;
use ReflectionException;
use Throwable;

/**
 * @implements IteratorAggregate<int, string>
 */
class ExtendedLoggerCaptionTrail implements IteratorAggregate {
    /** @var ExtendedLoggerCaptionTrail|null */
    private $parentCaptions;
    /** @var array<string, array<int, string>> */
    private $captions = [];
    /** @var int */
    private $couponCounter = 0;

    /**
     * @param ExtendedLoggerCaptionTrail|null $parentCaptions
     */
    public function __construct(?ExtendedLoggerCaptionTrail $parentCaptions = null) {
        $this->parentCaptions = $parentCaptions;
    }

	/**
	 * @param array<int, int|float|string|object> $captions
	 * @return string Coupon to address exactly this caption
	 */
    public function addCaptions(array $captions): string {
        $this->couponCounter++;
        $key = "caption-{$this->couponCounter}";
        $convertedCaptions = [];
        foreach($captions as $caption) {
			if(is_object($caption)) {
				try {
					$refC = new ReflectionClass($caption);
					$convertedCaptions[] = $refC->getShortName();
				} catch (ReflectionException $e) {
					$convertedCaptions[] = gettype($caption);
				}
			} else {
				$convertedCaptions[] = (string) $caption;
			}
		}
		$this->captions[$key] = $convertedCaptions;
        return $key;
    }

    /**
     * @return string[]
     */
    public function getCaptions(): array {
		$result = [];
		if($this->parentCaptions !== null) {
			foreach($this->parentCaptions->getCaptions() as $parentCaption) {
				$result[] = $parentCaption;
			}
		}
		foreach($this->captions as $captions) {
			foreach($this->_getCaptions($captions) as $caption) {
				$result[] = $caption;
			}
		}
        return $result;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function removeCaption(string $key): self {
        if(array_key_exists($key, $this->captions)) {
            unset($this->captions[$key]);
        }
        return $this;
    }

    /**
     * @return ArrayIterator<int, string>
     */
    public function getIterator(): ArrayIterator {
        return new ArrayIterator($this->getCaptions());
    }

    /**
     * @param array<int, string|object|array<int, mixed>> $captions
     * @return array<int, string>
     */
    private function _getCaptions(array $captions): array {
        $flatCaptions = [];
        foreach($captions as $caption) {
        	if(is_array($caption)) {
				/** @var array<int, string|object|array<int, mixed>> $caption */
				$subCaptions = $this->_getCaptions($caption);
				foreach($subCaptions as $subCaption) {
					$flatCaptions[] = $subCaption;
				}
			} else {
				$flatCaptions[] = $caption;
			}
        }

        $result = [];
		foreach($flatCaptions as $flatCaption) {
			if(is_object($flatCaption)) {
				if(method_exists($flatCaption, '__toString')) {
					$result[] = (string) $flatCaption;
				} else {
					try {
						$rc = new ReflectionClass($flatCaption);
						$result[] = $rc->getShortName();
					} catch (Throwable $e) {
						$entryParts = explode("\x5C", gettype($flatCaption));
						$result[] = array_slice($entryParts, -1, 1)[0];
					}
				}
				// @phpstan-ignore-next-line
			} elseif(!is_string($flatCaption)) {
				static $options = null;
				if($options === null) {
					$options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES;
					if(defined('JSON_THROW_ON_ERROR')) {
						$options |= constant('JSON_THROW_ON_ERROR');
					}
				}
				$result[] = json_encode($flatCaption, $options);
			} else {
				$result[] = $flatCaption;
			}
		}
        return $result;
    }
}
