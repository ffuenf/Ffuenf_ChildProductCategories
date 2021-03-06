<?php
/**
 * Ffuenf_ChildProductCategories extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category   Ffuenf
 * @package    Ffuenf_Pagespeed
 * @author     Achim Rosenhagen <a.rosenhagen@ffuenf.de>
 * @copyright  Copyright (c) 2015 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
*/

class Ffuenf_ChildProductCategories_Model_Observer_AssignCategories extends Varien_Event_Observer
{
    /**
     * Assign the categories of a configurable product to its child products on massaction
     *
     * @return Ffuenf_ChildProductCategories_Model_Observer_AssignCategories
     */
    public function detectProductAttributeChanges($observer)
    {
        $attributesData = $observer->getEvent()->getAttributesData();
        $productIds     = $observer->getEvent()->getProductIds();
        $user  = Mage::getSingleton('admin/session')->getUser();
        foreach ($productIds as $id) {
            $product = Mage::getModel('catalog/product')->load($id);
            if ($product->getTypeId() == 'configurable') {
                $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $product);
                $parentCategoryIds = $product->getCategoryIds();
                $childProductsIds = array();
                foreach ($childProducts as $childProduct) {
                    $childCategoryIds = $parentCategoryIds;
                    $childProductsIds[] = $childProduct->getId();
                    $childProduct->setCategoryIds($childCategoryIds);
                    $childProduct->setIsChanged(true);
                    $childProduct->save();
                }
                Mage::log('The categories of "'.$product->getName().'" ID:'.$product->getId().' has been assign to its child products '.implode(",",$childProductsIds));
            }
        }
        return $this;
    }

    /**
     * Assign the categories of a configurable product to its child products on product save
     *
     * @return Ffuenf_ChildProductCategories_Model_Observer_AssignCategories
     */
    public function detectProductChanges($observer)
    {
        /**
        * @var $product Mage_Catalog_Model_Product
        * @var $user    Mage_Admin_Model_User
        */
        if (Mage::helper('ffuenf_childproductcategories')->isExtensionActive()) {
            $product = $observer->getEvent()->getProduct();
            if ($product->getTypeId() == 'configurable') {
                $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $product);
                $parentCategoryIds = $product->getCategoryIds();
                $childProductsIds = array();
                foreach ($childProducts as $childProduct) {
                    $childCategoryIds = $parentCategoryIds;
                    $childProductsIds[] = $childProduct->getId();
                    $childProduct->setCategoryIds($childCategoryIds);
                    $childProduct->setIsChanged(true);
                    $childProduct->save();
                }
                Mage::log('The categories of "'.$product->getName().'" ID:'.$product->getId().' has been assign to its child products '.implode(",",$childProductsIds));
            }
        }
        return $this;
    }
}