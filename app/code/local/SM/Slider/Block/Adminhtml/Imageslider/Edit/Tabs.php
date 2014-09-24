<?php

class SM_Slider_Block_Adminhtml_Imageslider_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('imageslider_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('slider')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('slider')->__('Slider Information'),
          'title'     => Mage::helper('slider')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('slider/adminhtml_imageslider_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}