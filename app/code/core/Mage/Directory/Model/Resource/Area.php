<?php

class Mage_Directory_Model_Resource_Area extends Mage_Core_Model_Resource_Db_Abstract
{
	/**
     * Table with localized area names
     *
     * @var string
     */
    protected $_areaNameTable;

    /**
     * Define main and locale area name tables
     *
     */
    protected function _construct()
    {
        $this->_init('directory/country_area', 'area_id');
        $this->_areaNameTable = $this->getTable('directory/country_area_name');
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Mage_Core_Model_Abstract $object
     * 
     * @return Varien_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select  = parent::_getLoadSelect($field, $value, $object);
        $adapter = $this->_getReadAdapter();

        $locale       = Mage::app()->getLocale()->getLocaleCode();
        $systemLocale = Mage::app()->getDistroLocaleCode();

        $areaField = $adapter->quoteIdentifier($this->getMainTable() . '.' . $this->getIdFieldName());

        $condition = $adapter->quoteInto('lrn.locale = ?', $locale);
        $select->joinLeft(
            array('lrn' => $this->_areaNameTable),
            "{$areaField} = lrn.area_id AND {$condition}",
            array());

        if ($locale != $systemLocale) {
            $nameExpr  = $adapter->getCheckSql('lrn.area_id is null', 'srn.name', 'lrn.name');
            $condition = $adapter->quoteInto('srn.locale = ?', $systemLocale);
            $select->joinLeft(
                array('srn' => $this->_areaNameTable),
                "{$areaField} = srn.area_id AND {$condition}",
                array('name' => $nameExpr));
        } else {
            $select->columns(array('name'), 'lrn');
        }

        return $select;
    }

    /**
     * Load object by city id and code or default name
     *
     * @param Mage_Core_Model_Abstract $object
     * @param int $cityId
     * @param string $value
     * @param string $field
     * 
     * @return Mage_Directory_Model_Resource_Area
     */
    protected function _loadByCity($object, $cityId, $value, $field)
    {
        $adapter        = $this->_getReadAdapter();
        $locale         = Mage::app()->getLocale()->getLocaleCode();
        $joinCondition  = $adapter->quoteInto('rname.area_id = area.area_id AND rname.locale = ?', $locale);
        $select         = $adapter->select()
            ->from(array('area' => $this->getMainTable()))
            ->joinLeft(
                array('rname' => $this->_areaNameTable),
                $joinCondition,
                array('name'))
            ->where('area.city_id = ?', $cityId)
            ->where("area.{$field} = ?", $value);

        $data = $adapter->fetchRow($select);
        if ($data) {
            $object->setData($data);
        }

        $this->_afterLoad($object);

        return $this;
    }

    /**
     * Loads area by area code and city id
     *
     * @param Mage_Directory_Model_Area $area
     * @param string $areaCode
     * @param string $cityId
     *
     * @return Mage_Directory_Model_Resource_Area
     */
    public function loadByCode(Mage_Directory_Model_Area $area, $areaCode, $cityId)
    {
        return $this->_loadByCity($area, $cityId, (string)$areaCode, 'code');
    }

    /**
     * Load data by city id and default area name
     *
     * @param Mage_Directory_Model_Area $area
     * @param string $areaName
     * @param string $cityId
     * 
     * @return Mage_Directory_Model_Resource_Area
     */
    public function loadByName(Mage_Directory_Model_Area $area, $areaName, $cityId)
    {
        return $this->_loadByCity($area, $cityId, (string)$areaName, 'default_name');
    }
}
