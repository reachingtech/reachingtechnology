<?php

class Mage_Adminhtml_Block_Customer_Edit_Renderer_Area extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
        /**
        * Factory instance
        *
        * @var Mage_Core_Model_Abstract
        */
       protected $_factory;

       /**
        * Constructor for Mage_Adminhtml_Block_Customer_Edit_Renderer_Area class
        *
        * @param array $args
        */
       public function __construct(array $args = array())
       {
           $this->_factory = !empty($args['factory']) ? $args['factory'] : Mage::getSingleton('core/factory');
       }
	/**
	 * Output the area element and javasctipt that makes it dependent from country element
	 *
	 * @param Varien_Data_Form_Element_Abstract $element
	 * @return string
	 */
	public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $city = $element->getForm()->getElement('city_id');
        if (!is_null($city)) {
            Mage::log("go into area");
            $cityId = $city->getValue();
        } else {
            Mage::log("default area");
            return $element->getDefaultHtml();
        }

        $areaId = $element->getForm()->getElement('area_id')->getValue();
        $quoteStoreId = $element->getEntityAttribute()->getStoreId();

        $html = '<tr>';
        $element->setClass('input-text');
        $element->setRequired(true);
        $html .= '<td class="label">' . $element->getLabelHtml() . '</td><td class="value">';
        $html .= $element->getElementHtml();

        $selectName = str_replace('area', 'area_id', $element->getName());
        $selectId = $element->getHtmlId() . '_id';
        $html .= '<select id="' . $selectId . '" name="' . $selectName
            . '" class="select required-entry" style="display:none">';
        $html .= '<option value="">' . $this->_factory->getHelper('customer')->__('Please select') . '</option>';
        $html .= '</select>';

        $html .= '<script type="text/javascript">' . "\n";
        $html .= '$("' . $selectId . '").setAttribute("defaultValue", "' . $areaId.'");' . "\n";
        $html .= 'new areaUpdater("' . $city->getHtmlId() . '", "' . $element->getHtmlId() . '", "' .
            $selectId . '", ' . $this->helper('directory')->getAreaJsonByStore($quoteStoreId).');' . "\n";
        $html .= '</script>' . "\n";

        $html .= '</td></tr>' . "\n";

        return $html;
    }
}
