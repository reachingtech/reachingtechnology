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
 
class AHT_Aslideshow_Block_Adminhtml_Slideshow_Add_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('aslideshow_form', array('legend'=>Mage::helper('aslideshow')->__('General Information')));
        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
        ));

        $fieldset->addField('position', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Position'),
            'name'      => 'position',
            'values'    => Mage::getSingleton('aslideshow/config_source_position')->toOptionArray(),
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Sort Order'),
            'required'  => false,
            'name'      => 'sort_order',
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Is Active'),
            'name'      => 'is_active',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('stores', 'multiselect', array(
                'label'     => Mage::helper('aslideshow')->__('Visible In'),
                'required'  => true,
                'name'      => 'stores[]',
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
            ));
        }
        else {
            $fieldset->addField('stores', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
        }


        if( Mage::getSingleton('adminhtml/session')->getSlideshowData() ) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getSlideshowData());
            Mage::getSingleton('adminhtml/session')->setSlideshowData(null);
        }
        
        return parent::_prepareForm();
    }
}
