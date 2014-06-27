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
class AHT_Aslideshow_Block_Adminhtml_Slideshow_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('slideshow_product_grid');
        $this->setDefaultSort('entity_id');
        $this->setTitle(Mage::helper('aslideshow')->__('Product Information'));
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
        //echo '<pre>'; print_r($value);
        //$select = $this->getCollection()->getSelect();
        
        // Set custom filter for in category flag
        if ($column->getId() == 'in_slideshows') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
            }
            elseif(!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$productIds));
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
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('*')
  //          ->addAttributeToSelect('sku')
//            ->addAttributeToSelect('price')
            ->addStoreFilter($this->getRequest()->getParam('store'))
//            ->addAttributeToFilter('aslideshow_slideshow', $this->_getSlideshow()->getId());
//            ->addAttributeToFilter('type_id', array('in'=>array('simple')))
        ;
        $res = Mage::getSingleton('core/resource');
        $conn = $collection->getConnection();
        $collection->getSelect()->joinLeft(
            array('sproduct' => $res->getTableName('aslideshow/slideshow_product')), 
            $conn->quoteInto('sproduct.product_id=e.entity_id AND sproduct.slideshow_id=?', $this->_getSlideshow()->getSlideshowId()), 
            array()
        );
        //echo '<pre>'; print_r($collection->getSelect());
        //if ($this->isReadonly()) {
            $productIds = $this->_getSelectedProducts();
            //echo '<pre>'; print_r($productIds); 
            if (empty($productIds)) {
                $productIds = array(0);
            }
            //$collection->addFieldToFilter('entity_id', array('in' => $productIds));
        //}
        $this->setCollection($collection);
        
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('in_slideshows', array(
            'header_css_class' => 'a-center',
            'type'      => 'checkbox',
            'name'      => 'in_slideshows',
            'values'    => $this->_getSelectedProducts(),
            'align'     => 'center',
            'index'     => 'entity_id'
        ));
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('catalog')->__('ID'),
            'sortable'  => true,
            'width'     => '60',
            'index'     => 'entity_id'
        ));
        $this->addColumn('name_product', array(
            'header'    => Mage::helper('catalog')->__('Name'),
            'index'     => 'name'
        ));
        $this->addColumn('sku', array(
            'header'    => Mage::helper('catalog')->__('SKU'),
            'width'     => '80',
            'index'     => 'sku'
        ));
        $this->addColumn('price', array(
            'header'    => Mage::helper('catalog')->__('Price'),
            'type'  => 'currency',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'     => 'price'
        ));
        
        $this->addColumn('position', array(
            'header'            => Mage::helper('catalog')->__('Position'),
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
        return $this->getUrl('*/*/productGrid', array('_current'=>true));
    }

    protected function _getSelectedProducts()
    {
        $json = $this->getRequest()->getPost('products_slideshow');
        //echo '<pre>'; print_r($json);
        if (!is_null($json)) {
            //$products = array_keys((array)Zend_Json::decode($json));
            $products = $json;
        } else {
            //echo '<pre>'; print_r($this->_getSlideshow()->getProductId());
            //$products = array_keys($this->_getSlideshow()->getProductId());
            $products = $this->_getSlideshow()->getProductId();
        }
        return $products;
    }

    /**
     * Retrieve related products
     *
     * @return array
     */
    public function getSelectedSlideshowProducts()
    {
        $id = $this->getRequest()->getParam('id');
        $_slideshow = Mage::getModel('aslideshow/slideshow')->load($id);
        $products = array();
        $productArrs = $_slideshow->getProduct();
        if($productArrs) {
            foreach($productArrs as $productObj) {
                $products[$productObj['product_id']] = array('position' => $productObj['position']);
            }
        }
        return $products;
    }
}
