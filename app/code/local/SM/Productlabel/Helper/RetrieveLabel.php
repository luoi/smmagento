<?php
/**
 * Created by PhpStorm.
 * User: luoi
 * Date: 9/30/14
 * Time: 3:42 PM
 */
class SM_Productlabel_Helper_RetrieveLabel
{
    const TOP_LEFT = 1;
    const TOP_RIGHT = 2;
    const BOTTOM_LEFT = 3;
    const BOTTOM_RIGHT = 4;

    public function getLabel(array $productIdLabel)
    {
        if (! empty($productIdLabel)) {

            $labelCollection = Mage::getModel('productlabel/productlabel')->getCollection();
            $labelInfo = array();
            foreach ($labelCollection as $label) {
                $labelInfo[$label->getLabelId()] = array(
                        'imagename' => $label->getImageName(),
                        'class'  => 'product-label' . ' ' . $this->translatePositionToClassHtml($label->getPosition()),
                        'position' => $label->getPosition(),
                    )
                ;
            }// end foreach
            $result = array();
            foreach ($productIdLabel as $product) {
                $productId = $product['id'];
                $labels = $product['label'];
                $labelIds = explode(',', $labels);
                if (! empty($labelIds)) {
                    $exisPosition = array();
                    $imageInfo = array();
                    foreach ($labelIds as $id) {
                        if (isset($labelInfo[$id])) {
                            // check if position is exis then ignore this label
//                            Zend_debug::dump($exisPosition);
                            $flag = TRUE;
                            switch ($labelInfo[$id]['position']) {
                                case self::TOP_LEFT :
                                    if (is_null($exisPosition['top-left']) ){
                                        $exisPosition['top-left'] = TRUE;
                                        break;
                                    } else {
                                        $flag = FALSE;
                                    }
                                case self::TOP_RIGHT :
                                    if (is_null($exisPosition['top-right']) ){
                                        $exisPosition['top-right'] = TRUE;
                                        break;
                                    } else {
                                        $flag = FALSE;
                                    }
                                case self::BOTTOM_LEFT :
                                    if (is_null($exisPosition['bottom-left']) ){
                                        $exisPosition['bottom-left'] = TRUE;
                                        break;
                                    } else {
                                        $flag = FALSE;
                                    }
                                case self::BOTTOM_RIGHT :
                                    if (is_null($exisPosition['bottom-right']) ){
                                        $exisPosition['bottom-right'] = TRUE;
                                        break;
                                    } else {
                                        $flag = FALSE;
                                    }
                            }
                            if ($flag) $imageInfo[] = $labelInfo[$id];
                        }
                    } // end foreach
                    $result[$productId] = $imageInfo;
                } // end if $listLabelID
            } // end foreach
            return $result;
        } //end if $productIds
    } // end method getLabel()

    public function getPositionArray()
    {
        return array(
            array(
                'value'     => SM_Productlabel_Helper_RetrieveLabel::TOP_LEFT,
                'label'     => Mage::helper('productlabel')->__('Top Left'),
            ),

            array(
                'value'     => SM_Productlabel_Helper_RetrieveLabel::TOP_RIGHT,
                'label'     => Mage::helper('productlabel')->__('Top Right'),
            ),
            array(
                'value'     => SM_Productlabel_Helper_RetrieveLabel::BOTTOM_LEFT,
                'label'     => Mage::helper('productlabel')->__('Bottom Left'),
            ),
            array(
                'value'     => SM_Productlabel_Helper_RetrieveLabel::BOTTOM_RIGHT,
                'label'     => Mage::helper('productlabel')->__('Bottom Right'),
            ),
        );
    } // end method getPositionArray()

    public function translatePositionToClassHtml($positionCode = '')
    {
        if ($positionCode) {
            foreach ($this->getPositionArray() as $item) {
                if ($positionCode == $item['value']) {
                    $result = strtolower(preg_replace('/(.)\s([A-Z])/', "$1 $2", $item['label']));
                    return $result;
                } // end if
            } // end foreach
        } // end if
        return FALSE;
    } // end method translatePosition

    public function getPositionAsKeyValue()
    {
        $arr = array();
        foreach ($this->getPositionArray() as $item) {
            $arr[$item['value']] = $item['label'];
        } // end foreach
        return $arr;
    }// end method

    public function shouldShowLabel()
    {
        return Mage::getStoreConfig('sm_productlabel/sm_productlabel/enable');
    } // end method shouldShowLabel()
}// end class
// end file