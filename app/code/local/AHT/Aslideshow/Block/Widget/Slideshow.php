<?php
/**
 * AHT_Aslideshow Extension
 *
 * @category    Local
 * @package     AHT_Aslideshow
 * @author      dungnv (dungnv@arrowhitech.com)
 * @copyright   Copyright(c) 2011 Arrowhitech Inc. (http://www.arrowhitech.com)
 *
 */

/**
 *
 * @category   Local
 * @package    AHT_Aslideshow
 * @author     dungnv <dungnv@arrowhitech.com>
 */

class AHT_Aslideshow_Block_Widget_Slideshow
    extends AHT_Aslideshow_Block_Slideshow
    implements Mage_Widget_Block_Interface
{
    /**
     * Internal contructor
     *
     */
    protected function _construct()
    {
        parent::_construct();
    }

    /**
     * Retrieve how much products should be displayed.
     *
     * @return int
     */
    public function getAslideshowId()
    {
        if (!$this->hasData('aslideshow_id')) {
            return false;
        }
        return $this->_getData('aslideshow_id');
    }
}
