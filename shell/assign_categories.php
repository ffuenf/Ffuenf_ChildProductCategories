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

require_once 'abstract.php';

class Assign_Categories_Of_Parent_To_Simples extends Mage_Shell_Abstract
{
    const DEFAULT_BATCH_SIZE = 250;

    protected $report = array();
    protected $batchSize = self::DEFAULT_BATCH_SIZE;

    /**
     * @param $collection Varien_Data_Collection
     * @param array $callbackForIndividual
     * @param array $callbackAfterBatch
     * @param integer|null $batchSize
     */
    protected function walk($collection, array $callbackBeforeBatch, array $callbackForIndividual, array $callbackAfterBatch, $batchSize, $report)
    {
        $collection->setPageSize($batchSize);
        $currentPage = 1;
        $pages = $collection->getLastPageNumber();
        do {
            $collection->setCurPage($currentPage);
            $collection->load();
            $reportType = 'shell';
            $report[$reportType][$currentPage]['start']['time'] = microtime(true);
            $report[$reportType][$currentPage]['start']['memory'] = memory_get_usage(true);
            call_user_func($callbackBeforeBatch, $currentPage, $batchSize, $report);
            foreach ($collection as $item) {
                call_user_func($callbackForIndividual, $item, $currentPage, $batchSize, $report);
            }
            call_user_func($callbackAfterBatch, $currentPage, $batchSize, $report);
            
            $currentPage++;
            $collection->clear();
        } while ($currentPage <= $pages);
    }
    
    public function run()
    {
        $type = Mage::helper('ffuenf_childproductcategories')->getReassignCategoriesTimeframeType();
        $collection = Mage::getResourceModel('catalog/product_collection')
        ->addFieldToFilter(
            $type, array(
                'gt' => date("Y-m-d H:i:s", Mage::helper('ffuenf_childproductcategories')->getReassignCategoriesTimeframeFrom())
            )
        )->addFieldToFilter(
            $type, array(
                'lt' => date("Y-m-d H:i:s", Mage::helper('ffuenf_childproductcategories')->getReassignCategoriesTimeframeTo())
            )
        )->addFieldToFilter(
            'type_id', 'configurable'
        );
        
        $this->walk(
            $collection,
            array($this, 'batchBefore'),
            array($this, 'batchIndividual'),
            array($this, 'batchAfter'),
            self::DEFAULT_BATCH_SIZE,
            $report
        );
    }

    protected function batchBefore($currentPage, $batchSize, $report)
    {
    }

    protected function batchIndividual($model, $currentPage, $batchSize, $report)
    {
        $product = Mage::getModel('catalog/product')->load($model->getId());
        $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $product);
        $parentCategoryIds = $product->getCategoryIds();
        foreach ($childProducts as $childProduct) {
            $childCategoryIds = $childProduct->getCategoryIds();
            if (!Mage::helper('ffuenf_childproductcategories')->getReassignDelete()) {
                $childCategoryIds = $parentCategoryIds;
            } elseif (!empty($childCategoryIds) && Mage::helper('ffuenf_childproductcategories')->getReassignDelete()) {
                $childCategoryIds = '';
            } else {
                break;
            }
            $childProduct->setCategoryIds($childCategoryIds);
            $childProduct->setIsChanged(true);
            $childProduct->save();
            $childProduct->clearInstance();
        }
        $product->clearInstance();
    }

    protected function batchAfter($currentPage, $batchSize, $report)
    {
        $reportType = 'shell';
        $report[$reportType][$currentPage]['stop']['time'] = microtime(true);
        $report[$reportType][$currentPage]['stop']['memory'] = memory_get_usage(true);
        Ffuenf_Common_Model_Logger::logProfile(
            array(
                'class' => 'Ffuenf_ProductAlertCleaner',
                'type' => $reportType,
                'items' => $batchSize,
                'start' => array(
                    'time' => $report[$reportType][$page]['start']['time'],
                    'memory' => $report[$reportType][$page]['start']['memory'],
                ),
                'stop' => array(
                    'time' => $report[$reportType][$page]['stop']['time'],
                    'memory' => $report[$reportType][$page]['stop']['memory'],
                )
            )
        );
    }

    protected function convert($size)
    {
        $unit = array('B','KB','MB','GB','TB','PB');
        return @round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $unit[$i];
    }
}

$shell = new Assign_Categories_Of_Parent_To_Simples();
$shell->run();