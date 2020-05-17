<?php
namespace Logger\Common\ExtendedPsrLoggerWrapper;

use ArrayIterator;
use IteratorAggregate;
use ReflectionClass;
use ReflectionException;
use Throwable;

class ExtendedLoggerCaptionTrail implements IteratorAggregate {
    /** @var ExtendedLoggerCaptionTrail */
    private $parentCaptions;
    /** @var string[] */
    private $captions = [];
    /** @var int */
    private $couponCounter = 0;

    /**
     * @param ExtendedLoggerCaptionTrail $parentCaptions
     */
    public function __construct(ExtendedLoggerCaptionTrail $parentCaptions = null) {
        $this->parentCaptions = $parentCaptions;
    }

	/**
	 * @param string|string[]|object $caption
	 * @return string Coupon to address exactly this caption
	 */
    public function addCaption($caption): string {
        $this->couponCounter++;
        $key = "caption-{$this->couponCounter}";
        if(is_object($caption)) {
        	try {
				$refC = new ReflectionClass($caption);
				$caption = $refC->getShortName();
			} catch (ReflectionException $e) {
				$caption = gettype($caption);
			}
		}
		$this->captions[$key] = $caption;
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
		foreach($this->_getCaptions($this->captions) as $caption) {
			$result[] = $caption;
		}
        return $result;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function removeCaption($key) {
        if(array_key_exists($key, $this->captions)) {
            unset($this->captions[$key]);
        }
        return $this;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator {
        return new ArrayIterator($this->getCaptions());
    }

    /**
     * @param array $captions
     * @return string[]
     */
    private function _getCaptions(array $captions): array {
        $result = [];
        foreach($captions as $caption) {
            if(is_array($caption)) {
                $subCaptions = $this->_getCaptions($caption);
                foreach($subCaptions as $subCaption) {
                    $result[] = $subCaption;
                }
            } else {
                $result[] = $caption;
            }
        }
		foreach($result as &$entry) {
			if(is_object($entry)) {
				if(method_exists($entry, '__toString')) {
					$entry = (string) $entry;
				} else {
					try {
						$rc = new ReflectionClass($entry);
						$entry = $rc->getShortName();
					} catch (Throwable $e) {
						$entryParts = explode('\\', $entry);
						$entry = array_slice($entryParts, -1, 1)[0];
					}
				}
			} elseif(!is_string($entry)) {
				static $options = null;
				if($options === null) {
					$options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES;
					if(defined('JSON_THROW_ON_ERROR')) {
						$options |= constant('JSON_THROW_ON_ERROR');
					}
				}
				$entry = json_encode($entry, $options);
			}
		}
        return $result;
    }
}
