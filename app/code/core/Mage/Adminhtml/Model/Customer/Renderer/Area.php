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
 * Area field renderer
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Model_Customer_Renderer_Area implements Varien_Data_Form_Element_Renderer_Interface
{
	/**
	 * Country area collections
	 *
	 * array(
	 *      [$cityId] => Varien_Data_Collection_Db
	 * )
	 *
	 * @var array
	 */
	static protected $_areaCollections;

	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		$html = '<tr>'."\n";
                
                $cityId = 1;
		if ($city = $element->getForm()->getElement('city_id')) {
                    if (!is_null($city->getValue())) {
			$cityId = $city->getValue();
                    }
		}

		$areaCollection = false;
		if ($cityId) {
			if (!isset(self::$_areaCollections[$cityId])) {
				self::$_areaCollections[$cityId] = Mage::getModel('directory/city')
					->setId($cityId)
					->getLoadedAreaCollection()
                                        ->toOptionArray();
			}
			$areaCollection = self::$_areaCollections[$cityId];
		}

		$areaId = intval($element->getForm()->getElement('area_id')->getValue());

		$htmlAttributes = $element->getHtmlAttributes();
		foreach ($htmlAttributes as $key => $attribute) {
			if ('type' === $attribute) {
				unset($htmlAttributes[$key]);
				break;
			}
		}
        $areaHtmlName = $element->getName();
        $areaIdHtmlName = str_replace('area', 'area_id', $areaHtmlName);
        $areaHtmlId = $element->getHtmlId();
        $areaIdHtmlId = str_replace('area', 'area_id', $areaHtmlId);

        if ($areaCollection && count($areaCollection) > 0) {
            $elementClass = $element->getClass();
            $html.= '<td class="label">'.$element->getLabelHtml().'</td>';
            $html.= '<td class="value">';

            $html .= '<select id="' . $areaIdHtmlId . '" name="' . $areaIdHtmlName . '" '
                 . $element->serialize($htmlAttributes) .'>' . "\n";
            foreach ($areaCollection as $area) {
                $selected = ($areaId==$area['value']) ? ' selected="selected"' : '';
                $value =  is_numeric($area['value'])?(int)$area['value']:"";
                $html.= '<option value="'.$value.'"' . $selected . '>'
                    . Mage::helper('adminhtml')->escapeHtml(Mage::helper('directory')->__($area['label']))
                    . '</option>';
            }
            $html.= '</select>' . "\n";

            $html .= '<input type="hidden" name="' . $areaHtmlName . '" id="' . $areaHtmlId . '" value=""/>';

            $html.= '</td>';
            $element->setClass($elementClass);
        } else {
            $element->setClass('input-text');
            $html.= '<td class="label"><label for="'.$element->getHtmlId().'">'
                . $element->getLabel()
                . ' <span class="required" style="display:none">*</span></label></td>';

            $element->setRequired(false);
            $html.= '<td class="value">';
            $html .= '<input id="' . $areaHtmlId . '" name="' . $areaHtmlName
                . '" value="' . $element->getEscapedValue() . '" '
                . $element->serialize($htmlAttributes) . "/>" . "\n";
            $html .= '<input type="hidden" name="' . $areaIdHtmlName . '" id="' . $areaIdHtmlId . '" value=""/>';
            $html .= '</td>'."\n";
        }
        $html.= '</tr>'."\n";
        return $html;
	}

	protected function _getCityAreaUpdaterScript($cityId, $areaId, $areas)
	{
		return <<<EOT
(function(cityEl, areaEl, areas) {
	var cityEl = $(cityEl);
	var areaEl = $(areaEl);

	cityEl.observe('change', function(event) {
		var element = event.element();
		var value = element.getValue();

		areaEl.options.length = 0;
		if (areas[value] != undefined) {
			for (areaId in areas[value]) {
				area = areas[value][areaId];

				option = document.createElement('OPTION');
				option.value = areaId;
				option.text = area.name;

				if (areaEl.options.add) {
					areaEl.options.add(option);
				} else {
					areaEl.appendChild(option);
				}
			}
			fireEvent(areaEl, 'change');
		}
	});
})('{$cityId}', '{$areaId}', {$areas});
EOT;
	}
}