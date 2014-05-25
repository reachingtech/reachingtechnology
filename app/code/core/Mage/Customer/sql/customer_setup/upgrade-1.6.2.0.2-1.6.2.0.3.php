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
 * @package     Mage_Customer
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/* @var $installer Mage_Customer_Model_Entity_Setup */
$installer = $this;
/* @var $eavConfig Mage_Eav_Model_Config */
$eavConfig = Mage::getSingleton('eav/config');

// update customer system attributes used_in_forms data
$attributes = array(
    'firstname'         => array('is_required' => '0', 
                            ),
    'lastname'          => array('is_required' => '0', 
                            ),
    'email'             => array('is_required' => '0', 
                            ),
     'regmobile'             => array('is_required' => '0', 
                                'validate_rules' => 'a:2:{s:15:"max_text_length";i:11;s:15:"min_text_length";i:11;}',
                            ),
);

$defaultUsedInForms = array(
    'customer_account_create',
    'customer_account_edit',
    'checkout_register',
);

foreach ($attributes as $attributeCode => $data) {
    $attribute = $eavConfig->getAttribute('customer', $attributeCode);
    if (!$attribute) {
        continue;
    }
    $attribute->addData($data);
    $attribute->save();
}

// update customer address system attributes used_in_forms data
$attributes = array(
    'firstname'         => array('is_required' => '0', 
                            ),
    'lastname'          => array('is_required' => '0', 
                            ),
    'email'             => array('is_required' => '0', 
                            ),
);

$defaultUsedInForms = array(
    'adminhtml_customer_address',
    'customer_address_edit',
    'customer_register_address'
);

foreach ($attributes as $attributeCode => $data) {
    $attribute = $eavConfig->getAttribute('customer_address', $attributeCode);
    if (!$attribute) {
        continue;
    }
    $attribute->addData($data);
    $attribute->save();
}