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
class Ffuenf_ChildProductCategories_Helper_Data extends Ffuenf_Common_Helper_Data
{
    const CONFIG_EXTENSION_ACTIVE = 'ffuenf_childproductcategories/general/enable';
    const CONFIG_EXTENSION_REASSIGNCATEGORIES_TIMEFRAMEFROM = 'ffuenf_childproductcategories/reassigncategories/timeframefrom';
    const CONFIG_EXTENSION_REASSIGNCATEGORIES_TIMEFRAMETO = 'ffuenf_childproductcategories/reassigncategories/timeframeto';
    const CONFIG_EXTENSION_REASSIGNCATEGORIES_DELETE = 'ffuenf_childproductcategories/reassigncategories/delete';
    const CONFIG_EXTENSION_REASSIGNCATEGORIES_TIMEFRAMETYPE = 'ffuenf_childproductcategories/reassigncategories/timeframetype';
    const CONFIG_EXTENSION_LOG_ACTIVE = 'ffuenf_childproductcategories/log/enable';
    const CONFIG_EXTENSION_LOG_FILE = 'ffuenf_childproductcategories/log/file';
    const CONFIG_EXTENSION_LOG_FORCE = 'ffuenf_childproductcategories/log/force';
    const CONFIG_EXTENSION_LOG_ECHO = 'ffuenf_childproductcategories/log/echo';

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
     * Variable for the type of date (created_at / updated_at)
     *
     * @var string
     */
    protected $_sReassignCategoriesTimeframeType;

    /**
     * Variable for the timeframe of reassigning categories
     *
     * @var bool
     */
    protected $_bReassignDelete;

    /**
     * Variable for if the extension should log.
     *
     * @var bool
     */
    protected $_bDebug;

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

    /**
     * Get delete setting of assignment.
     *
     * @return string
     *
     * @throws Mage_Core_Exception
     */
    public function getReassignDelete()
    {
        return $this->getStoreFlag(self::CONFIG_EXTENSION_REASSIGNCATEGORIES_DELETE, '_bReassignDelete');
    }

    /**
     * Get the type of date (created_at / updated_at).
     *
     * @return string
     *
     * @throws Mage_Core_Exception
     */
    public function getReassignCategoriesTimeframeType()
    {
        return $this->getStoreConfig(self::CONFIG_EXTENSION_REASSIGNCATEGORIES_TIMEFRAMETYPE, '_sReassignCategoriesTimeframeType');
    }

    /**
     * Check to see if the extension is active.
     *
     * @return bool
     */
    public function isDebug()
    {
        return $this->getStoreFlag(self::CONFIG_EXTENSION_DEBUG, '_bDebug');
    }

    /**
     * Logs the given message in the specified log file..
     *
     * @param  mixed $message Log Message
     */
    public function log($message)
    {
        $logFile = Mage::getStoreConfig(self::CONFIG_EXTENSION_LOG_FILE);
        $forceLog = Mage::getStoreConfigFlag(self::CONFIG_EXTENSION_LOG_FORCE);
        $echoLog = Mage::getStoreConfigFlag(self::CONFIG_EXTENSION_LOG_ECHO);
        if ($logFile && strlen($logFile) > 0) {
            if ($echoLog) {
                echo $message;
            }
            Mage::log($message, Zend_Log::DEBUG, $logFile, $forceLog);
        }
        
        return $this;
    }
}
