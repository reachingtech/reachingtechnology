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
 
class AHT_Aslideshow_Model_Mysql4_Slideshow_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
    /**
     * Constructor method
     */
    protected function _construct() {
        $this->_init('aslideshow/slideshow');
    }

    /**
     * Add Filter by position
     *
     * @param string $position
     * @return AHT_Aslideshow_Model_Mysql4_Slideshow_Collection
     */
    public function addPositionFilter($position) {
        $this->getSelect()->where('main_table.slideshow_position = ?', $position);
        return $this;
    }

    /**
     * Add Filter by category
     *
     * @param int $category
     * @return AHT_Aslideshow_Model_Mysql4_Slideshow_Collection
     */
    public function addCategoryFilter($category) {
        $this->getSelect()->join(
                array('category_table' => $this->getTable('aslideshow/slideshow_category')),
                'main_table.slideshow_id = category_table.slideshow_id',
                array()
                )
                ->where('category_table.category_id = ?', $category);
        return $this;
    }

    public function addProductFilter($product) {
        $this->getSelect()->join(
                array('product_table' => $this->getTable('aslideshow/slideshow_product')),
                'main_table.slideshow_id = product_table.slideshow_id',
                array()
                )
                ->where('product_table.product_id = ?', $product);
        return $this;
    }

    public function addStaticblockFilter($staticblock) {
        $this->getSelect()->join(
                array('staticblock_table' => $this->getTable('aslideshow/slideshow_staticblock')),
                'main_table.slideshow_id = staticblock_table.slideshow_id',
                array()
                )
                ->where('staticblock_table.staticblock_id = ?', $staticblock);
        return $this;
    }

    /**
     * Add Filter by page
     *
     * @param int $page
     * @return AHT_Aslideshow_Model_Mysql4_Slideshow_Collection
     */
    public function addPageFilter($page) {
        $this->getSelect()->join(
                array('page_table' => $this->getTable('aslideshow/slideshow_page')),
                'main_table.slideshow_id = page_table.slideshow_id',
                array()
                )
                ->where('page_table.page_id = ?', $page);
        return $this;
    }

    /**
     * Add Filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @return AHT_Aslideshow_Model_Mysql4_Slideshow_Collection
     */
    public function addStoreFilter($store) {
        if (!Mage::app()->isSingleStoreMode()) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }

            $this->getSelect()->join(
                    array('store_table' => $this->getTable('aslideshow/slideshow_store')),
                    'main_table.slideshow_id = store_table.slideshow_id',
                    array()
                    )
                    ->where('store_table.store_id in (?)', array(0, $store));
            return $this;
        }
        return $this;
    }

    /**
     * Add Filter by status
     *
     * @param int $status
     * @return AHT_Aslideshow_Model_Mysql4_Slideshow_Collection
     */
    public function addEnableFilter($status = 1) {
        $this->getSelect()->where('main_table.is_active = ?', $status);
        return $this;
    }

    public function addSlideshowForFilter($slideshow_for) {
        $this->getSelect()->where('main_table.slideshow_for = ?', $slideshow_for);
        return $this;
    }
}
