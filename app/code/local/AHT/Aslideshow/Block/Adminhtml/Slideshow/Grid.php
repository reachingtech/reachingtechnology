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
 
class AHT_Aslideshow_Block_Adminhtml_Slideshow_Grid extends Mage_Adminhtml_Block_Widget_Grid {
    public function __construct() {
        parent::__construct();
        $this->setId('slideshowGrid');
        $this->setDefaultSort('date_from');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _getStore() {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('aslideshow/slideshow')->getCollection();
        $store = $this->_getStore();
        if ($store->getId()) {
            $collection->addStoreFilter($store);
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
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

        $this->addColumn('action',
                array(
                'header'    =>  Mage::helper('aslideshow')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                        array(
                                'caption'   => Mage::helper('aslideshow')->__('Edit'),
                                'url'       => array('base'=> '*/*/edit'),
                                'field'     => 'id'
                        ),
                        array(
                                'caption'   => Mage::helper('aslideshow')->__('Delete'),
                                'url'       => array('base'=> '*/*/delete'),
                                'field'     => 'id'
                        )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('slideshow_id');
        $this->getMassactionBlock()->setFormFieldName('slideshow');

        $this->getMassactionBlock()->addItem('delete', array(
                'label'    => Mage::helper('aslideshow')->__('Delete'),
                'url'      => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('aslideshow')->__('Are you sure?')
        ));

        $statuses = array(
                1 => Mage::helper('aslideshow')->__('Enabled'),
                2 => Mage::helper('aslideshow')->__('Disabled'));
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
                'label'=> Mage::helper('aslideshow')->__('Change status'),
                'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                        'visibility' => array(
                                'name' => 'status',
                                'type' => 'select',
                                'class' => 'required-entry',
                                'label' => Mage::helper('aslideshow')->__('Status'),
                                'values' => $statuses
                        )
                )
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
