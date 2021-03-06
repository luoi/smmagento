<?php
/**
 * Created by PhpStorm.
 * User: luoi
 * Date: 9/18/14
 * Time: 8:40 AM
 */
class SM_Featured_Block_Featured extends Mage_Catalog_Block_Product_New{
    /**
     * Prepare and return product collection
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection|Object|Varien_Data_Collection
     */
    protected function _getProductCollection()
    {
        $handleArray = Mage::app()->getLayout()->getUpdate()->getHandles();
        $categoryHandle = 'catalog_category_view';
        $homeHandle = 'cms_index_index';

        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());

        $limitFeatured = Mage::getStoreConfig('sm_featured/sm_featured_config/limitfeatured');
        if(ctype_digit($limitFeatured) && $limitFeatured > 0){
            $this->setProductsCount($limitFeatured);
        }

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
//            ->addAttributeToFilter('is_featured',1)
            ->setPageSize($this->getProductsCount())
            ->setCurPage(1)
        ;
        if(in_array($homeHandle, $handleArray)){
            $collection->addAttributeToFilter('is_featured',2);
        }
        // Filter by Category if it is in category page
        else if(in_array($categoryHandle, $handleArray)){
//            echo 'in cate';
            // if in home it's will not be shown in category
//            $collection->addAttributeToFilter('is_featured',1);

            // if in home it's also show in category
            $collection->addAttributeToFilter('is_featured',array('in'=>array('1','2')));
            $categoryId = Mage::getModel('catalog/layer')->getCurrentCategory()->getId();
            $categoryModel = Mage::getModel('catalog/category')->load($categoryId);
            $collection->addCategoryFilter($categoryModel)
            ;
        }
//        $collection->getSelect()->limit($this->getProductsCount());
        return $collection;
    } // end _getProductCollection

    public function _prepareLayout()
    {
//        echo __METHOD__;
        $featuredStatus = Mage::getStoreConfig('sm_featured/sm_featured/enable');
        if($featuredStatus == 1){
            $blockHead = Mage::app()->getLayout()->getBlock('head');
            $blockHead->addItem('skin_css', 'css/slider/lib/idangerous.swiper.css');
            $blockHead->addItem('skin_js', 'js/slider/lib/idangerous.swiper.js');
            $blockHead->addItem('skin_css', 'css/featured/featured.css');
        }


        return parent::_prepareLayout();
    } // end _prepareLayout()
} // end class
// end file