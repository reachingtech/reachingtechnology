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
 

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS `{$this->getTable('aslideshow/slideshow')}`;
CREATE TABLE `{$this->getTable('aslideshow/slideshow')}` (
    `slideshow_id` smallint(6) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) DEFAULT '',
    `slideshow_for` varchar(50) DEFAULT NULL,
    `product_ids` text,
    `slideshow_position` varchar(128) DEFAULT NULL,
    `transition` varchar(6) DEFAULT NULL,
    `auto_rotation` tinyint(1) DEFAULT NULL,
    `auto_rotation_speed` varchar(3) DEFAULT NULL,
    `slide_controlls` varchar(6) DEFAULT NULL,
    `animation_speed` int(6) DEFAULT NULL,
    `between_block_delay` varchar(6) DEFAULT NULL,
    `display` varchar(25) DEFAULT NULL,
    `blocksize_width` varchar(4) NOT NULL DEFAULT '80',
    `image_width` int(5) NOT NULL,
    `image_height` varchar(5) NOT NULL,
    `blocksize_height` varchar(4) NOT NULL DEFAULT '80',
    `show_text` tinyint(1) NOT NULL DEFAULT '1',
    `background_opacity` varchar(3) DEFAULT '1',
    `show_price` tinyint(1) NOT NULL DEFAULT '1',
    `show_title` tinyint(1) NOT NULL DEFAULT '1',
    `show_description` varchar(20) NOT NULL,
    `product_image_width` int(5) DEFAULT NULL,
    `product_image_height` int(5) DEFAULT NULL,
    `staticblock_image_width` int(5) DEFAULT NULL,
    `staticblock_image_height` int(5) DEFAULT NULL,
    `sort_order` smallint(5) DEFAULT '0',
    `is_active` tinyint(1) DEFAULT '1',
    PRIMARY KEY (`slideshow_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='My Aslideshow' ;

-- DROP TABLE IF EXISTS `{$this->getTable('aslideshow/slideshow_image')}`;
CREATE TABLE `{$this->getTable('aslideshow/slideshow_image')}` (
    `image_id` smallint(6) NOT NULL AUTO_INCREMENT,
    `label` varchar(255) DEFAULT NULL,
    `caption` varchar(255) DEFAULT NULL,
    `file` varchar(255) DEFAULT NULL,
    `position` smallint(5) DEFAULT '0',
    `disabled` tinyint(1) DEFAULT '1',
    `slideshow_id` smallint(6) DEFAULT '0',
    PRIMARY KEY (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='My Aslideshow Image' ;

-- DROP TABLE IF EXISTS `{$this->getTable('aslideshow/slideshow_product')}`;
CREATE TABLE `{$this->getTable('aslideshow/slideshow_product')}` (
    `slideshow_id` smallint(6) unsigned NOT NULL,
    `product_id` int(10) unsigned NOT NULL,
    `position` int(11) DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='My Aslideshow Product' ;

-- DROP TABLE IF EXISTS `{$this->getTable('aslideshow/slideshow_staticblock')}`;
CREATE TABLE `{$this->getTable('aslideshow/slideshow_staticblock')}` (
    `slideshow_id` int(10) unsigned NOT NULL,
    `staticblock_id` int(10) unsigned NOT NULL,
    `staticblock_identifier` varchar(255) DEFAULT NULL,
    `position` int(11) DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='My Aslideshow Product' ;

-- DROP TABLE IF EXISTS `{$this->getTable('aslideshow/slideshow_category')}`;
CREATE TABLE `{$this->getTable('aslideshow/slideshow_category')}` (
    `slideshow_id` smallint(6) NOT NULL,
    `category_id` smallint(6) NOT NULL,
    PRIMARY KEY (`slideshow_id`,`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='My Aslideshow Category' ;

-- DROP TABLE IF EXISTS `{$this->getTable('aslideshow/slideshow_page')}`;
CREATE TABLE `{$this->getTable('aslideshow/slideshow_page')}` (
    `slideshow_id` smallint(6) NOT NULL,
    `page_id` smallint(6) NOT NULL,
    PRIMARY KEY (`slideshow_id`,`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='My Aslideshow Page' ;

-- DROP TABLE IF EXISTS `{$this->getTable('aslideshow/slideshow_store')}`;
CREATE TABLE `{$this->getTable('aslideshow/slideshow_store')}` (
    `slideshow_id` smallint(6) NOT NULL,
    `store_id` smallint(6) NOT NULL,
    PRIMARY KEY (`slideshow_id`,`store_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='My Aslideshow Store' ;

    ");

$installer->endSetup();
