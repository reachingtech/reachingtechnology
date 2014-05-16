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
 * Directory data helper
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Directory_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Config value that lists ISO2 country codes which have optional Zip/Postal pre-configured
     */
    const OPTIONAL_ZIP_COUNTRIES_CONFIG_PATH = 'general/country/optional_zip_countries';

    /*
     * Path to config value, which lists countries, for which state is required.
     */
    const XML_PATH_STATES_REQUIRED = 'general/region/state_required';

    /*
     * Path to config value, which detects whether or not display the state for the country, if it is not required
     */
    const XML_PATH_DISPLAY_ALL_STATES = 'general/region/display_all';

    /**
     * Country collection
     *
     * @var Mage_Directory_Model_Resource_Country_Collection
     */
    protected $_countryCollection;

    /**
     * Region collection
     *
     * @var Mage_Directory_Model_Resource_Region_Collection
     */
    protected $_regionCollection;

    /**
     * Json representation of regions data
     *
     * @var string
     */
    protected $_regionJson;

    protected $_cityCollection;
    protected $_cityJson;
    protected $_areaCollection;
    protected $_areaJson;

    /**
     * Currency cache
     *
     * @var array
     */
    protected $_currencyCache = array();

    /**
     * ISO2 country codes which have optional Zip/Postal pre-configured
     *
     * @var array
     */
    protected $_optionalZipCountries = null;

    /**
     * Factory instance
     *
     * @var Mage_Core_Model_Factory
     */
    protected $_factory;

    /**
     * Application instance
     *
     * @var Mage_Core_Model_App
     */
    protected $_app;

    /**
     * Constructor for Mage_Directory_Helper_Data
     * @param array $args
     */
    public function __construct(array $args = array())
    {
        $this->_factory = !empty($args['factory']) ? $args['factory'] : Mage::getSingleton('core/factory');
        $this->_app = !empty($args['app']) ? $args['app'] : Mage::app();
    }

    /**
     * Retrieve region collection
     *
     * @return Mage_Directory_Model_Resource_Region_Collection
     */
    public function getRegionCollection()
    {
        if (!$this->_regionCollection) {
            $this->_regionCollection = Mage::getModel('directory/region')->getResourceCollection();
            if (method_exists($this, 'getAddress')) 
                $this->_regionCollection = $this->_regionCollection->addCountryFilter($this->getAddress()->getCountryId());
            $this->_regionCollection->load();
        }
        return $this->_regionCollection;
    }

    /**
     * Retrieve country collection
     *
     * @return Mage_Directory_Model_Resource_Country_Collection
     */
    public function getCountryCollection()
    {
        if (!$this->_countryCollection) {
            $this->_countryCollection = $this->_factory->getModel('directory/country')->getResourceCollection();
        }
        return $this->_countryCollection;
    }

    /**
     * Retrieve regions data json
     *
     * @deprecated after 1.7.0.2
     * @see Mage_Directory_Helper_Data::getRegionJsonByStore()
     * @return string
     */
    public function getRegionJson()
    {
        return $this->getRegionJsonByStore();
    }

    /**
     * Retrieve regions data json
     *
     * @param int|null $storeId
     * @return array()
     */
    public function getRegionJsonByStore($storeId = null)
    {
        Varien_Profiler::start('TEST: '.__METHOD__);
        if (!$this->_regionJson) {
            $store = $this->_app->getStore($storeId);
            $cacheKey = 'DIRECTORY_REGIONS_JSON_STORE' . (string)$store->getId();
            if ($this->_app->useCache('config')) {
                $json = $this->_app->loadCache($cacheKey);
            }
            if (empty($json)) {
                $regions = $this->_getRegions($storeId);
                $helper = $this->_factory->getHelper('core');
                $json = $helper->jsonEncode($regions);

                if ($this->_app->useCache('config')) {
                    $this->_app->saveCache($json, $cacheKey, array('config'));
                }
            }
            $this->_regionJson = $json;
        }

        Varien_Profiler::stop('TEST: ' . __METHOD__);
        return $this->_regionJson;
    }

    /**
     * Get Regions for specific Countries
     * @param string $storeId
     * @return array|null
     */
    protected function _getRegions($storeId)
    {
        $countryIds = array();

        $countryCollection = $this->getCountryCollection()->loadByStore($storeId);
        foreach ($countryCollection as $country) {
            $countryIds[] = $country->getCountryId();
        }

        /** @var $regionModel Mage_Directory_Model_Region */
        $regionModel = $this->_factory->getModel('directory/region');
        /** @var $collection Mage_Directory_Model_Resource_Region_Collection */
        $collection = $regionModel->getResourceCollection()
            ->addCountryFilter($countryIds)
            ->load();

        $regions = array(
            'config' => array(
                'show_all_regions' => $this->getShowNonRequiredState(),
                'regions_required' => $this->getCountriesWithStatesRequired()
            )
        );
        foreach ($collection as $region) {
            if (!$region->getRegionId()) {
                continue;
            }
            $regions[$region->getCountryId()][$region->getRegionId()] = array(
                'code' => $region->getCode(),
                'name' => $this->__($region->getName())
            );
        }
        return $regions;
    }

    /**
     * Retrieve city collection
     *
     * @return Mage_Directory_Model_Resource_City_Collection
     */
    public function getCityCollection()
    {
        if (!$this->_cityCollection) {
            $this->_cityCollection = Mage::getModel('directory/city')->getResourceCollection();
            if (method_exists($this, 'getAddress')) 
                $this->_cityCollection = $this->_cityCollection->addRegionFilter($this->getAddress()->getRegionId());
            $this->_cityCollection->load();
        }
        return $this->_cityCollection;
    }

    /**
     * Retrieve citys data json
     *
     * @deprecated after 1.7.0.2
     * @see Mage_Directory_Helper_Data::getCityJsonByStore()
     * @return string
     */
    public function getCityJson()
    {
        Varien_Profiler::start('TEST: '.__METHOD__);
        if (!$this->_cityJson) {
            $cacheKey = 'DIRECTORY_CITIES_JSON_STORE'.Mage::app()->getStore()->getId();
            if (Mage::app()->useCache('config')) {
                $json = Mage::app()->loadCache($cacheKey);
            }
            if (empty($json)) {
                $regionIds = array();
                foreach ($this->getRegionCollection() as $region) {
                    $regionIds[] = $region->getRegionId();
                }
                $collection = Mage::getModel('directory/city')->getResourceCollection()
                    ->addRegionFilter($regionIds)
                    ->load();
                $cities = array();
                foreach ($collection as $city) {
                    if (!$city->getCityId()) {
                        continue;
                    }
                    $cities[$city->getRegionId()][$city->getCityId()] = array(
                        'name'=>$city->getName()
                    );
                }
                $json = Mage::helper('core')->jsonEncode($cities);

                if (Mage::app()->useCache('config')) {
                    Mage::app()->saveCache($json, $cacheKey, array('config'));
                }
            }
            $this->_cityJson = $json;
        }

        Varien_Profiler::stop('TEST: '.__METHOD__);
        return $this->_cityJson;
    }

    /**
     * Retrieve citys data json
     *
     * @param int|null $storeId
     * @return array()
     */
    public function getCityJsonByStore($storeId = null)
    {
        Varien_Profiler::start('TEST: '.__METHOD__);
        if (!$this->_cityJson) {
            $store = $this->_app->getStore($storeId);
            $cacheKey = 'DIRECTORY_CITIES_JSON_STORE' . (string)$store->getId();
            if ($this->_app->useCache('config')) {
                $json = $this->_app->loadCache($cacheKey);
            }
            if (empty($json)) {
                $citys = $this->_getCitys($storeId);
                $helper = $this->_factory->getHelper('core');
                $json = $helper->jsonEncode($citys);

                if ($this->_app->useCache('config')) {
                    $this->_app->saveCache($json, $cacheKey, array('config'));
                }
            }
            $this->_cityJson = $json;
        }

        Varien_Profiler::stop('TEST: ' . __METHOD__);
        return $this->_cityJson;
    }

    /**
     * Get Citys for specific Countries
     * @param string $storeId
     * @return array|null
     */
    protected function _getCitys($storeId)
    {
        $regionIds = array();

        $regionCollection = $this->getRegionCollection()->loadByStore($storeId);
        foreach ($regionCollection as $region) {
            $regionIds[] = $region->getRegionId();
        }

        /** @var $cityModel Mage_Directory_Model_City */
        $cityModel = $this->_factory->getModel('directory/city');
        /** @var $collection Mage_Directory_Model_Resource_City_Collection */
        $collection = $cityModel->getResourceCollection()
            ->addRegionFilter($regionIds)
            ->load();

        $citys = array(
            'config' => array(
                'show_all_citys' => $this->getShowNonRequiredState(),
                'citys_required' => $this->getCountriesWithStatesRequired()
            )
        );
        foreach ($collection as $city) {
            if (!$city->getCityId()) {
                continue;
            }
            $citys[$city->getRegionId()][$city->getCityId()] = array(
                'code' => $city->getCode(),
                'name' => $this->__($city->getName())
            );
        }
        return $citys;
    }
    
    /**
     * Retrieve area collection
     *
     * @return Mage_Directory_Model_Resource_Area_Collection
     */
    public function getAreaCollection()
    {
        if (!$this->_areaCollection) {
            Mage::log("function getAreaCollection");
            $this->_areaCollection = Mage::getModel('directory/area')->getResourceCollection();
            if (method_exists($this, 'getAddress')) 
                $this->_areaCollection = $this->_areaCollection->addCityFilter($this->getAddress()->getCityId());
            $this->_areaCollection->load();
            Mage::log("function getAreaCollection end");
        }
        return $this->_areaCollection;
    }

    /**
     * Retrieve areas data json
     *
     * @deprecated after 1.7.0.2
     * @see Mage_Directory_Helper_Data::getAreaJsonByStore()
     * @return string
     */
    public function getAreaJson()
    {
        Varien_Profiler::start('TEST: '.__METHOD__);
        if (!$this->_areaJson) {
            $cacheKey = 'DIRECTORY_AREAS_JSON_STORE'.Mage::app()->getStore()->getId();
            if (Mage::app()->useCache('config')) {
                $json = Mage::app()->loadCache($cacheKey);
            }
            if (empty($json)) {
                $cityIds = array();
                foreach ($this->getCityCollection() as $city) {
                    $cityIds[] = $city->getCityId();
                }
                $collection = Mage::getModel('directory/area')->getResourceCollection()
                    ->addCityFilter($cityIds)
                    ->load();
                $areas = array();
                foreach ($collection as $area) {
                    if (!$area->getAreaId()) {
                        continue;
                    }
                    $areas[$area->getCityId()][$area->getAreaId()] = array(
                        'name'=>$area->getName()
                    );
                }
                $json = Mage::helper('core')->jsonEncode($areas);

                if (Mage::app()->useCache('config')) {
                    Mage::app()->saveCache($json, $cacheKey, array('config'));
                }
            }
            $this->_areaJson = $json;
        }

        Varien_Profiler::stop('TEST: '.__METHOD__);
        return $this->_areaJson;
    }

    /**
     * Retrieve areas data json
     *
     * @param int|null $storeId
     * @return array()
     */
    public function getAreaJsonByStore($storeId = null)
    {
        Varien_Profiler::start('TEST: '.__METHOD__);
        if (!$this->_areaJson) {
            $store = $this->_app->getStore($storeId);
            $cacheKey = 'DIRECTORY_AREAS_JSON_STORE' . (string)$store->getId();
            if ($this->_app->useCache('config')) {
                $json = $this->_app->loadCache($cacheKey);
            }
            if (empty($json)) {
                $areas = $this->_getAreas($storeId);
                $helper = $this->_factory->getHelper('core');
                $json = $helper->jsonEncode($areas);

                if ($this->_app->useCache('config')) {
                    $this->_app->saveCache($json, $cacheKey, array('config'));
                }
            }
            $this->_areaJson = $json;
        }

        Varien_Profiler::stop('TEST: ' . __METHOD__);
        return $this->_areaJson;
    }

    /**
     * Get Areas for specific Countries
     * @param string $storeId
     * @return array|null
     */
    protected function _getAreas($storeId)
    {
        $cityIds = array();

        $cityCollection = $this->getCityCollection()->loadByStore($storeId);
        foreach ($cityCollection as $city) {
            $cityIds[] = $city->getCityId();
        }

        /** @var $areaModel Mage_Directory_Model_Area */
        $areaModel = $this->_factory->getModel('directory/area');
        /** @var $collection Mage_Directory_Model_Resource_Area_Collection */
        $collection = $areaModel->getResourceCollection()
            ->addCityFilter($cityIds)
            ->load();

        $areas = array(
            'config' => array(
                'show_all_areas' => $this->getShowNonRequiredState(),
                'areas_required' => $this->getCountriesWithStatesRequired()
            )
        );
        foreach ($collection as $area) {
            if (!$area->getAreaId()) {
                continue;
            }
            $areas[$area->getCityId()][$area->getAreaId()] = array(
                'code' => $area->getCode(),
                'name' => $this->__($area->getName())
            );
        }
        return $areas;
    }

    /**
     * Convert currency
     *
     * @param float $amount
     * @param string $from
     * @param string $to
     * @return float
     */
    public function currencyConvert($amount, $from, $to = null)
    {
        if (empty($this->_currencyCache[$from])) {
            $this->_currencyCache[$from] = Mage::getModel('directory/currency')->load($from);
        }
        if (is_null($to)) {
            $to = Mage::app()->getStore()->getCurrentCurrencyCode();
        }
        $converted = $this->_currencyCache[$from]->convert($amount, $to);
        return $converted;
    }

    /**
     * Return ISO2 country codes, which have optional Zip/Postal pre-configured
     *
     * @param bool $asJson
     * @return array|string
     */
    public function getCountriesWithOptionalZip($asJson = false)
    {
        if (null === $this->_optionalZipCountries) {
            $this->_optionalZipCountries = preg_split('/\,/',
                Mage::getStoreConfig(self::OPTIONAL_ZIP_COUNTRIES_CONFIG_PATH), 0, PREG_SPLIT_NO_EMPTY);
        }
        if ($asJson) {
            return Mage::helper('core')->jsonEncode($this->_optionalZipCountries);
        }
        return $this->_optionalZipCountries;
    }

    /**
     * Check whether zip code is optional for specified country code
     *
     * @param string $countryCode
     * @return boolean
     */
    public function isZipCodeOptional($countryCode)
    {
        $this->getCountriesWithOptionalZip();
        return in_array($countryCode, $this->_optionalZipCountries);
    }

    /**
     * Returns the list of countries, for which region is required
     *
     * @param boolean $asJson
     * @return array
     */
    public function getCountriesWithStatesRequired($asJson = false)
    {
        $countryList = explode(',', Mage::getStoreConfig(self::XML_PATH_STATES_REQUIRED));
        if ($asJson) {
            return Mage::helper('core')->jsonEncode($countryList);
        }
        return $countryList;
    }

    /**
     * Return flag, which indicates whether or not non required state should be shown
     *
     * @return bool
     */
    public function getShowNonRequiredState()
    {
        return (boolean)Mage::getStoreConfig(self::XML_PATH_DISPLAY_ALL_STATES);
    }

    /**
     * Returns flag, which indicates whether region is required for specified country
     *
     * @param string $countryId
     * @return bool
     */
    public function isRegionRequired($countryId)
    {
        $countyList = $this->getCountriesWithStatesRequired();
        if (!is_array($countyList)) {
            return false;
        }
        return in_array($countryId, $countyList);
    }

	/**
	 * default country
	 * @return string
	 */
	public function getDefaultCountry()
	{
		return Mage::getStoreConfig('general/country/default');
	}
	
	/**
	 * more country
	 * @return string
	 */
	public function getMoreCountry()
	{
		return Mage::getStoreConfig('general/country/more');
	}
	
	/**
	 * allow country
	 * @return string
	 */
	public function getAllowCountry()
	{
		return Mage::getStoreConfig('general/country/allow');
	}
	
	/**
	 * optional zip countries country
	 * @return string
	 */
	public function getOptionalZipCountriesCountry()
	{
		return Mage::getStoreConfig('general/country/optional_zip_countries');
	}
}
