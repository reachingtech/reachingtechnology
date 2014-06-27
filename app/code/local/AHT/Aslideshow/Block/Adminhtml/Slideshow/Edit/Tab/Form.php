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
 
class AHT_Aslideshow_Block_Adminhtml_Slideshow_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $_model = Mage::registry('slideshow_data');
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('aslideshow_form', array('legend'=>Mage::helper('aslideshow')->__('General Information')));
        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
            'value'     => $_model->getName()
        ));

        $fieldset->addField('slideshow_for', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Slideshow for'),
            'name'      => 'slideshow_for',
            'values'    => Mage::getSingleton('aslideshow/config_source_position')->toSlideshowForArray(),
            'value'     => $_model->getSlideshowFor()
        ));

        $fieldset->addField('slideshow_position', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Position'),
            'name'      => 'slideshow_position',
            'values'    => Mage::getSingleton('aslideshow/config_source_position')->toOptionArray(),
            'value'     => $_model->getSlideshowPosition()
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Sort Order'),
            'required'  => false,
            'name'      => 'sort_order',
            'value'     => $_model->getSortOrder()
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Is Active'),
            'name'      => 'is_active',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value'     => $_model->getIsActive()
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('stores', 'multiselect', array(
                'label'     => Mage::helper('aslideshow')->__('Visible In'),
                'required'  => true,
                'name'      => 'stores[]',
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
                'value'     => $_model->getStoreId()
            ));
        }
        else {
            $fieldset->addField('stores', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
        }
        //new form
        $fieldset = $form->addFieldset('aslideshow_form2', array('legend'=>Mage::helper('aslideshow')->__('Global Slideshow Setting')));

        $fieldset->addField('show_text', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Show Text'),
            'name'      => 'show_text',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value'     => $_model->getShowText()
        ));

        $fieldset->addField('image_width', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Slideshow Width'),
            'required'  => true,
            'name'      => 'image_width',
            'value'     => $_model->getImageWidth()
        ));

        $fieldset->addField('image_height', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Slideshow Height'),
            'required'  => true,
            'name'      => 'image_height',
            'value'     => $_model->getImageHeight()
        ));

        $fieldset->addField('blocksize_width', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Block Size Width'),
            'name'      => 'blocksize_width',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toBlocksizeArray(),
            'value'     => $_model->getBlocksizeWidth()
        ));

        $fieldset->addField('blocksize_height', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Block Size Height'),
            'name'      => 'blocksize_height',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toBlocksizeArray(),
            'value'     => $_model->getBlocksizeHeight()
        ));

        $fieldset->addField('transition', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Transition'),
            'name'      => 'transition',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTransitionArray(),
            'value'     => $_model->getTransition()
        ));

        $fieldset->addField('auto_rotation', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Auto Rotation'),
            'name'      => 'auto_rotation',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value'     => $_model->getAutoRotation()
        ));

        $fieldset->addField('auto_rotation_speed', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Auto Rotation Speed'),
            'required'  => true,
            'name'      => 'auto_rotation_speed',
            'value'     => $_model->getAutoRotationSpeed()
        ));

        $fieldset->addField('slide_controlls', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Slide Controllers'),
            'name'      => 'slide_controlls',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toSlideControllsArray(),
            'value'     => $_model->getSlideControlls()
        ));

        $fieldset->addField('animation_speed', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Animation Speed'),
            'required'  => true,
            'name'      => 'animation_speed',
            'value'     => $_model->getAnimationSpeed()
        ));

        $fieldset->addField('between_block_delay', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Between Block Delay'),
            'required'  => true,
            'name'      => 'between_block_delay',
            'value'     => $_model->getBetweenBlockDelay()
        ));

        $fieldset->addField('display', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Display'),
            'name'      => 'display',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toDisplayArray(),
            'value'     => $_model->getDisplay()
        ));

        $fieldset->addField('background_opacity', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Background Opacity'),
            'name'      => 'background_opacity',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toOpacityArray(),
            'value'     => $_model->getBackgroundOpacity()
        ));
        //setting for product
        $fieldset = $form->addFieldset('aslideshow_form3', array('legend'=>Mage::helper('aslideshow')->__('Slideshow Product Setting')));

        $fieldset->addField('product_image_width', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Image Width'),
            'required'  => true,
            'name'      => 'product_image_width',
            'value'     => $_model->getProductImageWidth()
        ));

        $fieldset->addField('product_image_height', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Image Height'),
            'required'  => true,
            'name'      => 'product_image_height',
            'value'     => $_model->getProductImageHeight()
        ));

        $fieldset->addField('show_price', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Show Price'),
            'name'      => 'show_price',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value'     => $_model->getShowPrice()
        ));

        $fieldset->addField('show_title', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Show Title'),
            'name'      => 'show_title',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value'     => $_model->getShowTitle()
        ));

        $fieldset->addField('show_description', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Show Description'),
            'name'      => 'show_description',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toShowDescriptionArray(),
            'value'     => $_model->getShowDescription()
        ));

        //setting for product
        $fieldset = $form->addFieldset('aslideshow_form4', array('legend'=>Mage::helper('aslideshow')->__('Slideshow Static Block Setting')));

        $fieldset->addField('staticblock_image_width', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Image Width'),
            'required'  => true,
            'name'      => 'staticblock_image_width',
            'value'     => $_model->getStaticblockImageWidth()
        ));

        $fieldset->addField('staticblock_image_height', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Image Height'),
            'required'  => true,
            'name'      => 'staticblock_image_height',
            'value'     => $_model->getStaticblockImageHeight()
        ));

        if( Mage::getSingleton('adminhtml/session')->getSlideshowData() ) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getSlideshowData());
            Mage::getSingleton('adminhtml/session')->setSlideshowData(null);
        }
        
        return parent::_prepareForm();
    }
}
