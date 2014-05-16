<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Directory
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * City collection
 *
 * @category    Mage
 * @package     Mage_Directory
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Directory_Model_Resource_Area_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Locale area name table name
     *
     * @var string
     */
    protected $_areaNameTable;

    /**
     * City table name
     *
     * @var string
     */
    protected $_cityTable;

    /**
     * Define main, city, locale area name tables
     *
     */
    protected function _construct()
    {
        $this->_init('directory/area');

        $this->_cityTable    = $this->getTable('directory/country_city');
        $this->_areaNameTable = $this->getTable('directory/country_area_name');

        $this->addOrder('name', Varien_Data_Collection::SORT_ORDER_ASC);
        $this->addOrder('default_name', Varien_Data_Collection::SORT_ORDER_ASC);
    }

    /**
     * Initialize select object
     *
     * @return Mage_Directory_Model_Resource_Area_Collection
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $locale = Mage::app()->getLocale()->getLocaleCode();

        $this->addBindParam(':area_locale', $locale);
        $this->getSelect()->joinLeft(
            array('rname' => $this->_areaNameTable),
            'main_table.area_id = rname.area_id AND rname.locale = :area_locale',
            array('name'));

        return $this;
    }

    /**
     * Filter by city_id
     *
     * @param string|array $cityId
     * @return Mage_Directory_Model_Resource_Area_Collection
     */
    public function addCityFilter($cityId)
    {
        if (!empty($cityId)) {
            if (is_array($cityId)) {
                $this->addFieldToFilter('main_table.city_id', array('in' => $cityId));
            } else {
                $this->addFieldToFilter('main_table.city_id', $cityId);
            }
        }
        return $this;
    }

    /**
     * Filter by city code (ISO 3)
     *
     * @param string $cityCode
     * @return Mage_Directory_Model_Resource_Area_Collection
     */
    public function addCityCodeFilter($cityCode)
    {
        $this->getSelect()
            ->joinLeft(
                array('city' => $this->_cityTable),
                'main_table.city_id = city.city_id'
                )
            ->where('city.iso3_code = ?', $cityCode);

        return $this;
    }

    /**
     * Filter by Area code
     *
     * @param string|array $areaCode
     * @return Mage_Directory_Model_Resource_Area_Collection
     */
    public function addAreaCodeFilter($areaCode)
    {
        if (!empty($areaCode)) {
            if (is_array($areaCode)) {
                $this->addFieldToFilter('main_table.code', array('in' => $areaCode));
            } else {
                $this->addFieldToFilter('main_table.code', $areaCode);
            }
        }
        return $this;
    }

    /**
     * Filter by area name
     *
     * @param string|array $areaName
     * @return Mage_Directory_Model_Resource_Area_Collection
     */
    public function addAreaNameFilter($areaName)
    {
        if (!empty($areaName)) {
            if (is_array($areaName)) {
                $this->addFieldToFilter('main_table.default_name', array('in' => $areaName));
            } else {
                $this->addFieldToFilter('main_table.default_name', $areaName);
            }
        }
        return $this;
    }

    /**
     * Filter area by its code or name
     *
     * @param string|array $area
     * @return Mage_Directory_Model_Resource_Area_Collection
     */
    public function addAreaCodeOrNameFilter($area)
    {
        if (!empty($area)) {
            $condition = is_array($area) ? array('in' => $area) : $area;
            $this->addFieldToFilter(array('main_table.code', 'main_table.default_name'), array($condition, $condition));
        }
        return $this;
    }

    /**
     * Convert collection items to select options array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = $this->_toOptionArray('area_id', 'default_name', array('title' => 'default_name'));
        if (count($options) > 0) {
            array_unshift($options, array(
                'title '=> null,
                'value' => "",
                'label' => Mage::helper('directory')->__('-- Please select --')
            ));
        }
        return $options;
    }
}
