<?php
/**
 * AHT_Aslideshow Extension
 *
 * @category    Local
 * @package     AHT_Aslideshow
 * @author      dungnv (dungnv@arrowhitech.com)
 * @copyright   Copyright(c) 2011 Arrowhitech Inc. (http://www.arrowhitech.com)
 *
 */

/**
 *
 * @category   Local
 * @package    AHT_Aslideshow
 * @author     dungnv <dungnv@arrowhitech.com>
 */
 
class AHT_Aslideshow_Model_Slideshow extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('aslideshow/slideshow');
    }

    /*
     * Load image
     */
    public function getImageList() {
        if (!$this->hasData('image')) {
            $_object = $this->_getResource()->loadImage($this);
        }
        return $this->getData('image');
    }

    public function getProductList() {
        if (!$this->hasData('product')) {
            $_object = $this->_getResource()->loadProduct($this);
        }
        return $this->getData('product');
    }

    public function getStaticblockList() {
        if (!$this->hasData('staticblock')) {
            $_object = $this->_getResource()->loadStaticblock($this);
        }
        return $this->getData('staticblock');
    }
}
