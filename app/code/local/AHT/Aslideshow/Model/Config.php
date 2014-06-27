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
 
class AHT_Aslideshow_Model_Config extends Mage_Catalog_Model_Product_Media_Config {

    public function getBaseMediaPath() {
        return Mage::getBaseDir('media') .DS. 'aslideshow';
    }

    public function getBaseMediaUrl() {
        return Mage::getBaseUrl('media') . 'aslideshow';
    }

    public function getBaseTmpMediaPath() {
        return Mage::getBaseDir('media') .DS. 'tmp' .DS. 'aslideshow';
    }

    public function getBaseTmpMediaUrl() {
        return Mage::getBaseUrl('media') . 'tmp/aslideshow';
    }

}