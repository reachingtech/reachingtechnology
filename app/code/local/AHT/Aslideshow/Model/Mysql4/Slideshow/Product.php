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
 
class AHT_Aslideshow_Model_Mysql4_Slideshow_Product extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('aslideshow/slideshow_product', 'slideshow_product_id');
    }
}
