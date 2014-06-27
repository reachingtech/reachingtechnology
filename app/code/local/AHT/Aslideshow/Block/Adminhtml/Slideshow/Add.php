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

class AHT_Aslideshow_Block_Adminhtml_Slideshow_Add extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId    = 'id';
        $this->_blockGroup  = 'aslideshow';
        $this->_controller  = 'adminhtml_slideshow';

        $this->_updateButton('save', 'label', Mage::helper('aslideshow')->__('Save Slideshow'));
    }

    public function getHeaderText()
    {
        Mage::helper('aslideshow')->__("Add Slideshow");
    }
}
