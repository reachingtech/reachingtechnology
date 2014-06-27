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

class AHT_Aslideshow_Block_Adminhtml_Slideshow_Edit_Tab_Page extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $_model = Mage::registry('slideshow_data');
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('aslideshow_form', array('legend'=>Mage::helper('aslideshow')->__('Slideshow Pages')));
        $fieldset->addField('pages', 'multiselect', array(
            'label'     => Mage::helper('aslideshow')->__('Visible In'),
            'required'  => true,
            'name'      => 'pages[]',
            'values'    => Mage::getSingleton('aslideshow/config_source_page')->toOptionArray(),
            'value'     => $_model->getPageId()
        ));
        
        return parent::_prepareForm();
    }
}
