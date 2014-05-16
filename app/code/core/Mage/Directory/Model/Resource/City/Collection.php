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
 * Region collection
 *
 * @category    Mage
 * @package     Mage_Directory
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Directory_Model_Resource_City_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Locale city name table name
     *
     * @var string
     */
    protected $_cityNameTable;

    /**
     * Region table name
     *
     * @var string
     */
    protected $_regionTable;

    /**
     * Define main, region, locale city name tables
     *
     */
    protected function _construct()
    {
        $this->_init('directory/city');

        $this->_regionTable    = $this->getTable('directory/country_region');
        $this->_cityNameTable = $this->getTable('directory/country_city_name');

        $this->addOrder('name', Varien_Data_Collection::SORT_ORDER_ASC);
        $this->addOrder('default_name', Varien_Data_Collection::SORT_ORDER_ASC);
    }

    /**
     * Initialize select object
     *
     * @return Mage_Directory_Model_Resource_City_Collection
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $locale = Mage::app()->getLocale()->getLocaleCode();

        $this->addBindParam(':city_locale', $locale);
        $this->getSelect()->joinLeft(
            array('rname' => $this->_cityNameTable),
            'main_table.city_id = rname.city_id AND rname.locale = :city_locale',
            array('name'));

        return $this;
    }

    /**
     * Filter by region_id
     *
     * @param string|array $regionId
     * @return Mage_Directory_Model_Resource_City_Collection
     */
    public function addRegionFilter($regionId)
    {
        if (!empty($regionId)) {
            if (is_array($regionId)) {
                $this->addFieldToFilter('main_table.region_id', array('in' => $regionId));
            } else {
                $this->addFieldToFilter('main_table.region_id', $regionId);
            }
        }
        return $this;
    }

    /**
     * Filter by region code (ISO 3)
     *
     * @param string $regionCode
     * @return Mage_Directory_Model_Resource_City_Collection
     */
    public function addRegionCodeFilter($regionCode)
    {
        $this->getSelect()
            ->joinLeft(
                array('region' => $this->_regionTable),
                'main_table.region_id = region.region_id'
                )
            ->where('region.iso3_code = ?', $regionCode);

        return $this;
    }

    /**
     * Filter by City code
     *
     * @param string|array $cityCode
     * @return Mage_Directory_Model_Resource_City_Collection
     */
    public function addCityCodeFilter($cityCode)
    {
        if (!empty($cityCode)) {
            if (is_array($cityCode)) {
                $this->addFieldToFilter('main_table.code', array('in' => $cityCode));
            } else {
                $this->addFieldToFilter('main_table.code', $cityCode);
            }
        }
        return $this;
    }

    /**
     * Filter by city name
     *
     * @param string|array $cityName
     * @return Mage_Directory_Model_Resource_City_Collection
     */
    public function addCityNameFilter($cityName)
    {
        if (!empty($cityName)) {
            if (is_array($cityName)) {
                $this->addFieldToFilter('main_table.default_name', array('in' => $cityName));
            } else {
                $this->addFieldToFilter('main_table.default_name', $cityName);
            }
        }
        return $this;
    }

    /**
     * Filter city by its code or name
     *
     * @param string|array $city
     * @return Mage_Directory_Model_Resource_City_Collection
     */
    public function addCityCodeOrNameFilter($city)
    {
        if (!empty($city)) {
            $condition = is_array($city) ? array('in' => $city) : $city;
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
        $options = $this->_toOptionArray('city_id', 'default_name', array('title' => 'default_name'));
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
