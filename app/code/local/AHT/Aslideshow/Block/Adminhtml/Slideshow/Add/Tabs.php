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
 
class AHT_Aslideshow_Block_Adminhtml_Slideshow_Add_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('aslideshow_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('aslideshow')->__('Slideshow Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general_section', array(
            'label'     => Mage::helper('aslideshow')->__('General Information'),
            'title'     => Mage::helper('aslideshow')->__('General Information'),
            'content'   => $this->getLayout()->createBlock('aslideshow/adminhtml_slideshow_add_tab_form')->toHtml(),
        ))->addTab('image_section', array(
            'label'     => Mage::helper('aslideshow')->__('Slideshow Images'),
            'title'     => Mage::helper('aslideshow')->__('Slideshow Images'),
            'content'   => $this->getLayout()->createBlock('aslideshow/adminhtml_slideshow_add_tab_image')->toHtml(),
        ))->addTab('page_section', array(
            'label'     => Mage::helper('aslideshow')->__('Display on Pages'),
            'title'     => Mage::helper('aslideshow')->__('Display on Pages'),
            'content'   => $this->getLayout()->createBlock('aslideshow/adminhtml_slideshow_add_tab_page')->toHtml(),
        ))->addTab('category_section', array(
            'label'     => Mage::helper('aslideshow')->__('Display on Categories'),
            'title'     => Mage::helper('aslideshow')->__('Display on Categories'),
            'content'   => $this->getLayout()->createBlock('aslideshow/adminhtml_slideshow_add_tab_category')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}