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
 
class AHT_Aslideshow_Block_Adminhtml_Slideshow extends Mage_Adminhtml_Block_Widget_Grid_Container {
    public function __construct() {
        $this->_controller = 'adminhtml_slideshow';
        $this->_blockGroup = 'aslideshow';
        $this->_headerText = Mage::helper('aslideshow')->__('Slideshow Manager');
        parent::__construct();

        $this->setTemplate('aht_aslideshow/slideshow.phtml');
    }

    protected function _prepareLayout() {
        $this->setChild('add_new_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label'     => Mage::helper('aslideshow')->__('Add Slideshow'),
                'onclick'   => "setLocation('".$this->getUrl('*/*/add')."')",
                'class'   => 'add'
                ))
        );
        /**
         * Display store switcher if system has more one store
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->setChild('store_switcher',
                    $this->getLayout()->createBlock('adminhtml/store_switcher')
                    ->setUseConfirm(false)
                    ->setSwitchUrl($this->getUrl('*/*/*', array('store'=>null)))
            );
        }
        $this->setChild('grid', $this->getLayout()->createBlock('aslideshow/adminhtml_slideshow_grid', 'slideshow.grid'));
        return parent::_prepareLayout();
    }

    public function getAddNewButtonHtml() {
        return $this->getChildHtml('add_new_button');
    }

    public function getGridHtml() {
        return $this->getChildHtml('grid');
    }

    public function getStoreSwitcherHtml() {
        return $this->getChildHtml('store_switcher');
    }
}
