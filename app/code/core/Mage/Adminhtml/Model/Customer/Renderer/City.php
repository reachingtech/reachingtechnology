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
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * REgion field renderer
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Model_Customer_Renderer_City implements Varien_Data_Form_Element_Renderer_Interface
{
	/**
	 * Country city collections
	 *
	 * array(
	 *      [$regionId] => Varien_Data_Collection_Db
	 * )
	 *
	 * @var array
	 */
	static protected $_cityCollections;

	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		$html = '<tr>'."\n";
                $regionId = 1;
		if ($region = $element->getForm()->getElement('region_id')) {
                    if (!is_null($region->getValue())) {
                        $regionId = $region->getValue();
                    }
                        
		}

		$cityCollection = false;
		if ($regionId) {
			if (!isset(self::$_cityCollections[$regionId])) {
				self::$_cityCollections[$regionId] = Mage::getModel('directory/region')
					->setId($regionId)
					->getLoadedCityCollection()
                                        ->toOptionArray();
			}
			$cityCollection = self::$_cityCollections[$regionId];
		}

		$cityId = intval($element->getForm()->getElement('city_id')->getValue());

		$htmlAttributes = $element->getHtmlAttributes();
		foreach ($htmlAttributes as $key => $attribute) {
			if ('type' === $attribute) {
				unset($htmlAttributes[$key]);
				break;
			}
		}
        $cityHtmlName = $element->getName();
        $cityIdHtmlName = str_replace('city', 'city_id', $cityHtmlName);
        $cityHtmlId = $element->getHtmlId();
        $cityIdHtmlId = str_replace('city', 'city_id', $cityHtmlId);

        if ($cityCollection && count($cityCollection) > 0) {
            $elementClass = $element->getClass();
            //$html.= '<td class="label">'.$element->getLabelHtml().'</td>';
            $html.= '<td class="label">'.$element->getLabelHtml().'</td>';
            $html.= '<td class="value">';

            $html .= '<select id="' . $cityIdHtmlId . '" name="' . $cityIdHtmlName . '" '
                 . $element->serialize($htmlAttributes) .'>' . "\n";
            foreach ($cityCollection as $city) {
                $selected = ($cityId==$city['value']) ? ' selected="selected"' : '';
                $value =  is_numeric($city['value'])?(int)$city['value']:"";
                $html.= '<option value="'.$value.'"' . $selected . '>'
                    . Mage::helper('adminhtml')->escapeHtml(Mage::helper('directory')->__($city['label']))
                    . '</option>';
            }
            $html.= '</select>' . "\n";

            $html .= '<input type="hidden" name="' . $cityHtmlName . '" id="' . $cityHtmlId . '" value=""/>';
            $html.= '<script type="text/javascript">';
            $html.= $this->_getRegionCityUpdaterScript($element->getForm()->getElement('region')->getHtmlId(), $element->getHtmlId(), Mage::helper('directory')->getCityJson());
            $html.= '</script>';
            $html.= '</td>';
            $element->setClass($elementClass);
        } else {
            $element->setClass('input-text');
            $html.= '<td class="label"><label for="'.$element->getHtmlId().'">'
                . $element->getLabel()
                . ' <span class="required" style="display:none">*</span></label></td>';

            $element->setRequired(false);
            $html.= '<td class="value">';
            $html .= '<input id="' . $cityHtmlId . '" name="' . $cityHtmlName
                . '" value="' . $element->getEscapedValue() . '" '
                . $element->serialize($htmlAttributes) . "/>" . "\n";
            $html .= '<input type="hidden" name="' . $cityIdHtmlName . '" id="' . $cityIdHtmlId . '" value=""/>';
            $html .= '</td>'."\n";
        }
        $html.= '</tr>'."\n";
        return $html;
	}

	protected function _getRegionCityUpdaterScript($regionId, $cityId, $cities)
	{
		return <<<EOT
(function(regionEl, cityEl, cities) {
	var regionEl = $(regionEl);
	var cityEl = $(cityEl);

	regionEl.observe('change', function(event) {
		var element = event.element();
		var value = element.getValue();

		cityEl.options.length = 0;
		if (cities[value] != undefined) {
			for (cityId in cities[value]) {
				city = cities[value][cityId];

				option = document.createElement('OPTION');
				option.value = cityId;
				option.text = city.name;

				if (cityEl.options.add) {
					cityEl.options.add(option);
				} else {
					cityEl.appendChild(option);
				}
			}
			fireEvent(cityEl, 'change');
		}
	});
})('{$regionId}', '{$cityId}', {$cities});
EOT;
	}
}