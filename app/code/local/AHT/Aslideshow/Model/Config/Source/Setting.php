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
 
class AHT_Aslideshow_Model_Config_Source_Setting
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toTransitionArray()
    {
        return array(
            array('value' => 'slide', 'label'=>Mage::helper('adminhtml')->__('Slide')),
            array('value' => 'fade', 'label'=>Mage::helper('adminhtml')->__('Fade')),
            array('value' => 'drop', 'label'=>Mage::helper('adminhtml')->__('Drop'))
        );
    }


    public function toSlideControllsArray()
    {
        return array(
            array('value' => 'items', 'label'=>Mage::helper('adminhtml')->__('Yes')),
            array('value' => 'none', 'label'=>Mage::helper('adminhtml')->__('No'))
        );
    }

    public function toTrueFalseArray()
    {
        return array(
            array('value' => 'true', 'label'=>Mage::helper('adminhtml')->__('Yes')),
            array('value' => 'false', 'label'=>Mage::helper('adminhtml')->__('No'))
        );
    }

    public function toDisplayArray()
    {
        return array(
            array('value' => 'diagonaltop', 'label'=>Mage::helper('adminhtml')->__('Diagonal Top')),
            array('value' => 'diagonalbottom', 'label'=>Mage::helper('adminhtml')->__('Diagonal Bottom')),
            array('value' => 'topleft', 'label'=>Mage::helper('adminhtml')->__('Top Left')),
            array('value' => 'bottomright', 'label'=>Mage::helper('adminhtml')->__('Bottom Right')),
            array('value' => 'random', 'label'=>Mage::helper('adminhtml')->__('Random')),
            array('value' => 'all', 'label'=>Mage::helper('adminhtml')->__('All'))
        );
    }

    public function toShowDescriptionArray()
    {
        return array(
            array('value' => 'description', 'label'=>Mage::helper('adminhtml')->__('Description')),
            array('value' => 'short_description', 'label'=>Mage::helper('adminhtml')->__('Short Description')),
            array('value' => 'no', 'label'=>Mage::helper('adminhtml')->__('No'))
        );
    }

    public function toBlocksizeArray()
    {
        return array(
            array('value' => '3', 'label'=>Mage::helper('adminhtml')->__('3px')),
            array('value' => '5', 'label'=>Mage::helper('adminhtml')->__('5px')),
            array('value' => '7', 'label'=>Mage::helper('adminhtml')->__('7px')),
            array('value' => '10', 'label'=>Mage::helper('adminhtml')->__('10px')),
            array('value' => '20', 'label'=>Mage::helper('adminhtml')->__('20px')),
            array('value' => '30', 'label'=>Mage::helper('adminhtml')->__('30px')),
            array('value' => '40', 'label'=>Mage::helper('adminhtml')->__('40px')),
            array('value' => '50', 'label'=>Mage::helper('adminhtml')->__('50px')),
            array('value' => '60', 'label'=>Mage::helper('adminhtml')->__('60px')),
            array('value' => '70', 'label'=>Mage::helper('adminhtml')->__('70px')),
            array('value' => '80', 'label'=>Mage::helper('adminhtml')->__('80px')),
            array('value' => '90', 'label'=>Mage::helper('adminhtml')->__('90px')),
            array('value' => '100', 'label'=>Mage::helper('adminhtml')->__('100px')),
            array('value' => 'full', 'label'=>Mage::helper('adminhtml')->__('Full'))
        );
    }

    public function toOpacityArray()
    {
        return array(
            array('value' => '0.1', 'label'=>Mage::helper('adminhtml')->__('0.1')),
            array('value' => '0.2', 'label'=>Mage::helper('adminhtml')->__('0.2')),
            array('value' => '0.3', 'label'=>Mage::helper('adminhtml')->__('0.3')),
            array('value' => '0.4', 'label'=>Mage::helper('adminhtml')->__('0.4')),
            array('value' => '0.5', 'label'=>Mage::helper('adminhtml')->__('0.5')),
            array('value' => '0.6', 'label'=>Mage::helper('adminhtml')->__('0.6')),
            array('value' => '0.7', 'label'=>Mage::helper('adminhtml')->__('0.7')),
            array('value' => '0.8', 'label'=>Mage::helper('adminhtml')->__('0.8')),
            array('value' => '0.9', 'label'=>Mage::helper('adminhtml')->__('0.9')),
            array('value' => '1', 'label'=>Mage::helper('adminhtml')->__('1'))
        );
    }
}
