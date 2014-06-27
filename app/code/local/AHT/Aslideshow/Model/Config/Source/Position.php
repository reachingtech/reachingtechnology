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
 
class AHT_Aslideshow_Model_Config_Source_Position
{
    const CONTENT_TOP           = 'CONTENT_TOP';
    const CONTENT_BOTTOM        = 'CONTENT_BOTTOM';
    const LEFT_TOP              = 'LEFT_TOP';
    const LEFT_BOTTOM           = 'LEFT_BOTTOM';
    const RIGHT_TOP             = 'RIGHT_TOP';
    const RIGHT_BOTTOM          = 'RIGHT_BOTTOM';

    const SLIDESHOW_IMAGE           = 'SLIDESHOW_IMAGE';
    const SLIDESHOW_PRODUCT         = 'SLIDESHOW_PRODUCT';
    const SLIDESHOW_STATIC_BLOCK    = 'SLIDESHOW_STATIC_BLOCK';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::CONTENT_TOP, 'label'=>Mage::helper('adminhtml')->__('Content Top')),
            array('value' => self::CONTENT_BOTTOM, 'label'=>Mage::helper('adminhtml')->__('Content Bottom')),
            array('value' => self::LEFT_TOP, 'label'=>Mage::helper('adminhtml')->__('Left Top')),
            array('value' => self::LEFT_BOTTOM, 'label'=>Mage::helper('adminhtml')->__('Left Bottom')),
            array('value' => self::RIGHT_TOP, 'label'=>Mage::helper('adminhtml')->__('Right Top')),
            array('value' => self::RIGHT_BOTTOM, 'label'=>Mage::helper('adminhtml')->__('Right Bottom'))
        );
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toGridOptionArray()
    {
        return array(
            self::CONTENT_TOP => Mage::helper('adminhtml')->__('Content Top'),
            self::CONTENT_BOTTOM => Mage::helper('adminhtml')->__('Content Bottom'),
            self::LEFT_TOP => Mage::helper('adminhtml')->__('Left Top'),
            self::LEFT_BOTTOM => Mage::helper('adminhtml')->__('Left Bottom'),
            self::RIGHT_TOP => Mage::helper('adminhtml')->__('Right Top'),
            self::RIGHT_BOTTOM => Mage::helper('adminhtml')->__('Right Bottom')
        );
    }

    public function toSlideshowForArray()
    {
        return array(
            array('value' => self::SLIDESHOW_IMAGE, 'label'=>Mage::helper('adminhtml')->__('Image')),
            array('value' => self::SLIDESHOW_PRODUCT, 'label'=>Mage::helper('adminhtml')->__('Product')),
            array('value' => self::SLIDESHOW_STATIC_BLOCK, 'label'=>Mage::helper('adminhtml')->__('Static Block'))
        );
    }

    public function toGridSlideshowForArray()
    {
        return array(
            self::SLIDESHOW_IMAGE => Mage::helper('adminhtml')->__('Image'),
            self::SLIDESHOW_PRODUCT => Mage::helper('adminhtml')->__('Product'),
            self::SLIDESHOW_STATIC_BLOCK => Mage::helper('adminhtml')->__('Static Block')
        );
    }

}
