<?php
/**
 * Add City_Id/Area/Area_id/mobile attribute to quote_address
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2014 ReachingTech Inc.
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/* @var $installer Mage_Customer_Model_Entity_Setup */
$installer = $this;

// add quote address system attributes data
$attributes = array(
    'city_id'         => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 106,
        'is_required'       => 0,
    ),
    'area'            => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 107,
        'is_required'       => 0,
    ),
    'area_id'         => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 108,
        'is_required'       => 0,
    ),
    'mobile'          => array(
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 110,
        'is_required'       => 1,
        'validate_rules'    => array(
        ),
    ),

);

foreach ($attributes as $attributeCode => $data) {
    $installer->addAttribute('quote_address', $attributeCode, $data);
    }

