<?php
/** AHT_Aslideshow Extension
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
class AHT_Aslideshow_Block_Adminhtml_Slideshow_Widget_Chooser extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Block construction, prepare grid params
     *
     * @param array $arguments Object data
     */
    public function __construct($arguments=array())
    {
        parent::__construct($arguments);
        //$this->setDefaultSort('name');
        $this->setUseAjax(true);
//        $this->setDefaultFilter(array('is_active' => '1'));
    }

    /**
     * Prepare chooser element HTML
     *
     * @param Varien_Data_Form_Element_Abstract $element Form Element
     * @return Varien_Data_Form_Element_Abstract
     */
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getUrl('aslideshow/adminhtml_widget/chooser', array('uniq_id' => $uniqId));

        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
            ->setElement($element)
            ->setTranslationHelper($this->getTranslationHelper())
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);

        if ($element->getValue()) {
            $aslideshow = Mage::getModel('aslideshow/slideshow')->load((int)$element->getValue());
            if ($aslideshow->getId()) {
                $chooser->setLabel($aslideshow->getName());
            }
        }
        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * Grid Row JS Callback
     *
     * @return string
     */
    public function getRowClickCallback()
    {
        $chooserJsObject = $this->getId();
        $js = '
            function (grid, event) {
                var trElement = Event.findElement(event, "tr");
                var aslideshowName = trElement.down("td").next().innerHTML;
                var aslideshowId = trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                '.$chooserJsObject.'.setElementValue(aslideshowId);
                '.$chooserJsObject.'.setElementLabel(aslideshowName);
                '.$chooserJsObject.'.close();
            }
        ';
        return $js;
    }


    protected function _getStore() {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    /**
     * Prepare pages collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('aslideshow/slideshow')->getCollection();
        $store = $this->_getStore();
        if ($store->getId()) {
            $collection->addStoreFilter($store);
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns for pages grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('slideshow_id', array(
                'header'    => Mage::helper('aslideshow')->__('ID'),
                'align'     =>'right',
                'width'     => '50px',
                'index'     => 'slideshow_id',
        ));

        $this->addColumn('name', array(
                'header'    => Mage::helper('aslideshow')->__('Title'),
                'align'     =>'left',
                'index'     => 'name',
        ));

        $this->addColumn('slideshow_position', array(
                'header'    => Mage::helper('aslideshow')->__('Position'),
                'align'     => 'left',
                'width'     => '80px',
                'index'     => 'slideshow_position',
                'type'      => 'options',
                'options'   => Mage::getSingleton('aslideshow/config_source_position')->toGridOptionArray(),
        ));

        $this->addColumn('is_active', array(
                'header'    => Mage::helper('aslideshow')->__('Status'),
                'align'     => 'left',
                'width'     => '80px',
                'index'     => 'is_active',
                'type'      => 'options',
                'options'   => array(
                        1 => Mage::helper('aslideshow')->__('Enabled'),
                        0 => Mage::helper('aslideshow')->__('Disabled'),
                //3 => Mage::helper('aslideshow')->__('Hidden'),
                ),
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('aslideshow/adminhtml_widget/chooser', array('_current' => true));
    }
}
