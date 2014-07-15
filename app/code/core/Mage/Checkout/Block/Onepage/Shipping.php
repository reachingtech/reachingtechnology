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
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * One page checkout status
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Checkout_Block_Onepage_Shipping extends Mage_Checkout_Block_Onepage_Abstract
{
    /**
     * Sales Qoute Shipping Address instance
     *
     * @var Mage_Sales_Model_Quote_Address
     */
    protected $_address = null;

    /**
     * Initialize shipping address step
     */
    protected function _construct()
    {
        $this->getCheckout()->setStepData('shipping', array(
            'label'     => Mage::helper('checkout')->__('Shipping Information'),
            'is_show'   => $this->isShow()
        ));
        
        if ($this->isCustomerLoggedIn()) {
            $this->getCheckout()->setStepData('shipping', 'allow', true);
        }
        
        parent::_construct();
    }

    /**
     * Return checkout method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->getQuote()->getCheckoutMethod();
    }

    /**
     * Return Sales Quote Address model (shipping address)
     *
     * @return Mage_Sales_Model_Quote_Address
     */
    public function getAddress()
    {
        if (is_null($this->_address)) {
            if ($this->isCustomerLoggedIn()) {
                $this->_address = $this->getQuote()->getShippingAddress();
            } else {
                $this->_address = Mage::getModel('sales/quote_address');
            }
        }

        return $this->_address;
    }

    /**
     * Retrieve is allow and show block
     *
     * @return bool
     */
    public function isShow()
    {
        return !$this->getQuote()->isVirtual();
    }
    
    public function getAddAddressUrl()
    {
        return $this->getUrl('customer/address/new', array('_secure'=>true));
    }
    
    public function getShippingAddressSubPageHTML()
    {
        $html = '<div data-role="page" id="shipping_address_list">'.'<div class="page-title" data-role="header">';
        $html.='<a href="/" data-icon="back" data-iconpos="notext" data-rel="back">'.$this->__('Back').'</a>';
        $html.='<h1>'.$this->__('Address Book').'</h1>';
        $html.='<a href="'.$this->getAddAddressUrl().'">'.$this->__('New Address').'</a>';
        
        $html.='<ul class="form-list" data-role="listview">';
        if($_pAddsses = $this->getCustomer()->getAddresses()){
            foreach($_pAddsses as $_address){
                $html.='<li class="wide">';
                //$html.='<input type="text" name="shipping_address_id" id="shipping_address_id" value="'.$_address->getId().'" style="display:none"></input>';
                $html.='<a href="#page" onclick="jQuery(\\\'#selected_address\\\').html(jQuery(this).html());jQuery(\\\'#shipping_address_id\\\').val(jQuery(this).attr(\\\'id\\\'));" id="'.$_address->getId().'">'.$_address->format("jqueryhtml").'</a>';
                //$html.='<a href="#page">'.'hello <br/>hi<br/>'.'</a>';
                $html.='</li>';
            }
        }
        $html.='</ul>';
        //$html.='<script type="text/javascript">function returnFromAddressList(){jQuery("#selected_address").val(jQuery(this).val()); jQuery("#shipping_address_id").val(jQuery(this).attr("id"));}</script>';
        $html.='</div>';
        
        return $html;
    }
    
    public function switchDefaultShipping($address){
        $oldDefaultAddress = $this->getCustomer()->getDefaultShippingAddress();
        $oldDefaultAddress->setIsDefaultShipping(FALSE);
        $address->setIsDefaultShipping(TRUE);
    }
}
