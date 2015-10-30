<?php

/**
 * Ffuenf_ChildProductCategories extension.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category   Ffuenf
 *
 * @author     Achim Rosenhagen <a.rosenhagen@ffuenf.de>
 * @copyright  Copyright (c) 2015 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */
class Ffuenf_ChildProductCategories_Helper_Data extends Ffuenf_ChildProductCategories_Helper_Core
{
    const CONFIG_EXTENSION_ACTIVE = 'ffuenf_childproductcategories/general/enable';
    const CONFIG_EXTENSION_REASSIGNCATEGORIES_TIMEFRAMEFROM = 'ffuenf_childproductcategories/reassigncategories/timeframefrom';
    const CONFIG_EXTENSION_REASSIGNCATEGORIES_TIMEFRAMETO = 'ffuenf_childproductcategories/reassigncategories/timeframeto';

    /**
     * Variable for if the extension is active.
     *
     * @var bool
     */
    protected $_bExtensionActive;

    /**
     * Variable for the timeframe of reassigning categories
     *
     * @var string
     */
    protected $_sReassignCategoriesTimeframeFrom;

    /**
     * Variable for the timeframe of reassigning categories
     *
     * @var string
     */
    protected $_sReassignCategoriesTimeframeTo;

    /**
     * Check to see if the extension is active.
     *
     * @return bool
     */
    public function isExtensionActive()
    {
        return $this->getStoreFlag(self::CONFIG_EXTENSION_ACTIVE, '_bExtensionActive');
    }

    /**
     * Get timeframe of reassigning categories.
     *
     * @return string
     *
     * @throws Mage_Core_Exception
     */
    public function getReassignCategoriesTimeframeFrom()
    {
        return strtotime($this->getStoreConfig(self::CONFIG_EXTENSION_REASSIGNCATEGORIES_TIMEFRAMEFROM, '_sReassignCategoriesTimeframeFrom'));
    }

    /**
     * Get timeframe of reassigning categories.
     *
     * @return string
     *
     * @throws Mage_Core_Exception
     */
    public function getReassignCategoriesTimeframeTo()
    {
        return strtotime($this->getStoreConfig(self::CONFIG_EXTENSION_REASSIGNCATEGORIES_TIMEFRAMETO, '_sReassignCategoriesTimeframeTo'));
    }
}
