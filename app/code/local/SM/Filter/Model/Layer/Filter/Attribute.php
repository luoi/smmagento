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
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Layer attribute filter
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class SM_Filter_Model_Layer_Filter_Attribute extends Mage_Catalog_Model_Layer_Filter_Attribute
{
    protected $_filter;

    /**
     * Apply attribute option filter to product collection
     *
     * @param   Zend_Controller_Request_Abstract $request
     * @param   Varien_Object $filterBlock
     * @return  Mage_Catalog_Model_Layer_Filter_Attribute
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        $typeRenderer = $this->getAttributeModel()->getFilterFrontendRendererType();
//        echo $typeRenderer;
        $filterConfig = Mage::getStoreConfig('sm_filter/sm_filter');
        $arrTypeApplyMultiselect = explode(',', $filterConfig['multiselect']);
//        echo 'type';
//        Zend_debug::dump($typeRenderer);
//        echo 'inarray';
//        Zend_debug::dump(! in_array($typeRenderer, $arrTypeApplyMultiselect));
        if (! $filterConfig['enable'] || ! $filterConfig['enable_multiselect']
        || ! in_array($typeRenderer, $arrTypeApplyMultiselect)) {
            return parent::apply($request, $filterBlock);
        }
//        Zend_debug::dump($filterConfig);

//        die();
//        echo 'tiep tuc lamf viec';
//        echo $typeRenderer;
//        echo __METHOD__;
        $this->_filter = $request->getParam($this->_requestVar);
        if (is_array($this->_filter)) {
            return $this;
        }

        $filters = explode('_', $this->_filter);
        if ($this->_filter) { // && strlen($text)) {
            $this->_initItems();
//            $text = '';
            foreach ($filters as $item) {
//                // not filter if Option text empty
                $optionText = $this->_getOptionText($item);
                if ($optionText == '') {
                    unset($filters[array_search($item, $filters)]);
                    break;
                }
                $this->getLayer()->getState()->addFilter($this->_createItem($optionText, $item));
            } // end foreach $filters
            $this->_getResource()->applyFilterToCollection($this, $filters);//implode(',', $filters));
//            $this->getLayer()->getState()->addFilter($this->_createItem($text, $filter));
//            $this->_items = array(); delete all option becausce filtered

        }
        return $this;
    }

    public function getRequestValue()
    {
        return $this->_filter;
    }


}
