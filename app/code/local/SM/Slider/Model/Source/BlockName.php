<?php
/**
 * Created by PhpStorm.
 * User: luoi
 * Date: 10/3/14
 * Time: 10:30 PM
 */
class SM_Slider_Model_Source_BlockName
{
    public function toOptionArray()
    {
        return array(
            array('value' => '-', 'label' => Mage::helper('adminhtml')->__('All other') ),
            array('value' => 'show.featured.product', 'label' => Mage::helper('adminhtml')->__('Featured Block') ),
            array('value' => 'show.bestseller.product', 'label' => Mage::helper('adminhtml')->__('Bestseller Block') ),
            array('value' => 'category.products', 'label' => Mage::helper('adminhtml')->__('Category product list') ),
            array('value' => 'other', 'label' => Mage::helper('adminhtml')->__('Other') ),
        );
    }
} // end class
// end file