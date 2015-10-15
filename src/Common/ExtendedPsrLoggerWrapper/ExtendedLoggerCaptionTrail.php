<?php
namespace Logger\Common\ExtendedPsrLoggerWrapper;

use IteratorAggregate;
use Traversable;

class ExtendedLoggerCaptionTrail implements IteratorAggregate {
    /** @var ExtendedLoggerCaptionTrail */
    private $parentCaptions;
    /** @var string[] */
    private $captions = array();
    /** @var int */
    private $couponCounter = 0;

    /**
     * @param ExtendedLoggerCaptionTrail $parentCaptions
     */
    public function __construct(ExtendedLoggerCaptionTrail $parentCaptions = null) {
        $this->parentCaptions = $parentCaptions;
    }

    /**
     * @param string|string[] $caption
     * @return int Coupon to address exactly this caption
     */
    public function addCaption($caption) {
        $this->couponCounter++;
        $key = "caption-{$this->couponCounter}";
        $this->captions[$key] = $caption;
        return $key;
    }

    /**
     * @return string[]
     */
    public function getCaptions() {
		$result = array();
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
     * @return Traversable
     */
    public function getIterator() {
        return new \ArrayIterator($this->getCaptions());
    }

    /**
     * @param array $captions
     * @return string[]
     */
    private function _getCaptions(array $captions) {
        $result = array();
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
        return $result;
    }
}
