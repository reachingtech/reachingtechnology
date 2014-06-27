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
class AHT_Aslideshow_Block_Adminhtml_Slideshow_Edit_Tab_Staticblocks extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('slideshow_staticblock_grid');
        $this->setDefaultSort('block_identifier');
        $this->setTitle(Mage::helper('aslideshow')->__('Staticblock Information'));
        $this->setUseAjax(true);
    }

    protected function _getSlideshow()
    {
        return Mage::registry('current_slideshow');
    }

    protected function _addColumnFilterToCollection($column)
    {
        $id = $column->getId();
        $value = $column->getFilter()->getValue();
        
        // Set custom filter for in category flag
        if ($column->getId() == 'in_slideshows') {
            $staticblockIds = $this->_getSelectedStaticblocks();
            if (empty($staticblockIds)) {
                $staticblockIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('block_id', array('in'=>$staticblockIds));
            }
            elseif(!empty($staticblockIds)) {
                $this->getCollection()->addFieldToFilter('block_id', array('nin'=>$staticblockIds));
            }
        }
        else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
    
    

    protected function _prepareCollection()
    {
        if ($this->_getSlideshow()->getSlideshowId()) {
            $this->setDefaultFilter(array('in_slideshows'=>1));
        }

        $collection = Mage::getModel('cms/block')->getCollection();
        /* @var $collection Mage_Cms_Model_Mysql4_Block_Collection */
        
        $res = Mage::getSingleton('core/resource');
        $conn = $collection->getConnection();
        $collection->getSelect()->joinLeft(
            array('staticblock' => $res->getTableName('aslideshow/slideshow_staticblock')), 
            $conn->quoteInto('staticblock.staticblock_id=main_table.block_id AND staticblock.slideshow_id=?', $this->_getSlideshow()->getSlideshowId()), 
            array()
        );
        $staticblockIds = $this->_getSelectedStaticblocks();

        if (empty($staticblockIds)) {
            $staticblockIds = array(0);
        }
        $this->setCollection($collection);
        
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $baseUrl = $this->getUrl();
        $this->addColumn('in_slideshows', array(
            'header_css_class' => 'a-center',
            'type'      => 'checkbox',
            'name'      => 'in_slideshows',
            'values'    => $this->_getSelectedStaticblocks(),
            'align'     => 'center',
            'index'     => 'block_id'
        ));
        $this->addColumn('block_id', array(
            'header'    => Mage::helper('catalog')->__('ID'),
            'sortable'  => true,
            'width'     => '60',
            'index'     => 'block_id'
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('cms')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));

        $this->addColumn('identifier', array(
            'header'    => Mage::helper('cms')->__('Identifier'),
            'align'     => 'left',
            'index'     => 'identifier'
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('cms')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('is_active', array(
            'header'    => Mage::helper('cms')->__('Status'),
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array(
                0 => Mage::helper('cms')->__('Disabled'),
                1 => Mage::helper('cms')->__('Enabled')
            ),
        ));

        $this->addColumn('creation_time', array(
            'header'    => Mage::helper('cms')->__('Date Created'),
            'index'     => 'creation_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('update_time', array(
            'header'    => Mage::helper('cms')->__('Last Modified'),
            'index'     => 'update_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('position', array(
            'header'            => Mage::helper('cms')->__('Position'),
            'name'              => 'position',
            'type'              => 'number',
            'validate_class'    => 'validate-number',
            'index'             => 'position',
            'width'             => 60,
            'editable'          => true,//!$this->_getProduct()->getRelatedReadonly(),
            'edit_only'         => true//!$this->_getProduct()->getId()
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/staticblockGrid', array('_current'=>true));
    }

    protected function _getSelectedStaticblocks()
    {
        $json = $this->getRequest()->getPost('staticblocks_slideshow');
        if (!is_null($json)) {
            $staticblocks = $json;
        } else {
            $staticblocks = $this->_getSlideshow()->getStaticblockId();
        }
        return $staticblocks;
    }

    /**
     * Retrieve related staticblocks
     *
     * @return array
     */
    public function getSelectedSlideshowStaticblocks()
    {
        $id = $this->getRequest()->getParam('id');
        $_slideshow = Mage::getModel('aslideshow/slideshow')->load($id);
        $staticblocks = array();
        $staticblockArrs = $_slideshow->getStaticblock();
        if($staticblockArrs) {
            foreach($staticblockArrs as $staticblockObj) {
                $staticblocks[$staticblockObj['staticblock_id']] = array('position' => $staticblockObj['position']);
            }
        }
        return $staticblocks;
    }
}
