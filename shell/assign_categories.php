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

    /**
     * @param $collection Varien_Data_Collection
     * @param array $callbackForIndividual
     * @param array $callbackAfterBatch
     * @param integer|null $batchSize
     */
    public function walk($collection, array $callbackForIndividual, array $callbackAfterBatch, $batchSize = null)
    {
        if ($batchSize !== null) {
            $batchSize = self::DEFAULT_BATCH_SIZE;
        }
        $collection->setPageSize($batchSize);
        $currentPage = 1;
        $pages = $collection->getLastPageNumber();
        do {
            $collection->setCurPage($currentPage);
            $collection->load();
            foreach ($collection as $item) {
                call_user_func($callbackForIndividual, $item);
            }
            call_user_func($callbackAfterBatch);
            $currentPage++;
            $collection->clear();
        } while ($currentPage <= $pages);
    }
    
    public function run()
    {
        $collection = Mage::getResourceModel('catalog/product_collection')
        ->addFieldToFilter(
            'created_at', array(
                'gt' => date("Y-m-d H:i:s", Mage::helper('ffuenf_childproductcategories')->getReassignCategoriesTimeframeFrom())
            )
        )->addFieldToFilter(
            'created_at', array(
                'lt' => date("Y-m-d H:i:s", Mage::helper('ffuenf_childproductcategories')->getReassignCategoriesTimeframeTo())
            )
        )->addFieldToFilter(
            'type_id', 'configurable'
        );
        
        $this->walk(
            $collection,
            array($this, 'batchIndividual'),
            array($this, 'batchAfter'),
            self::DEFAULT_BATCH_SIZE
        );
    }

    public function batchIndividual($model)
    {
        $product = Mage::getModel('catalog/product')->load($model->getId());
        $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $product);
        $parentCategoryIds = $product->getCategoryIds();
        foreach ($childProducts as $childProduct) {
            $childCategoryIds = $parentCategoryIds;
            $childProduct->setCategoryIds($childCategoryIds);
            $childProduct->setIsChanged(true);
            echo $childProduct->getId()."\r\n";
            $childProduct->save();
        }
    }

    public function batchAfter($model)
    {
    }
}

$shell = new Assign_Categories_Of_Parent_To_Simples();
$shell->run();