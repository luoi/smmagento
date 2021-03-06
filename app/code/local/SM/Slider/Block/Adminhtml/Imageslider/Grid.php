<?php

class SM_Slider_Block_Adminhtml_Imageslider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
//      echo __METHOD__;
      parent::__construct();
      $this->setId('imageGrid');
      $this->setDefaultSort('image_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $sliderId = $this->getRequest()->getParam('filtersliderid');
      $collection = Mage::getModel('slider/imageslider')->getCollection();
      if($sliderId != ''){
          $collection = $collection->addFieldToFilter('slider_id', array('eq' => $sliderId));
      }
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
//      $this->getListSlider();
//      die();
      $this->addColumn('image_id', array(
          'header'    => Mage::helper('slider')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'image_id',
      ));

      $this->addColumn('imagepreview', array(
          'header'    => Mage::helper('slider')->__('Image Preview'),
          'align'     =>'left',
          'index'     => 'imagepreview',
          'sortable'      => false,
          'filter'      => false,
          'renderer' => 'slider/adminhtml_imageslider_renderer_imagepreview',
//          'renderer'  =>   'salestaff/adminhtml_staff_renderer_avatar',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('slider')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));

      $this->addColumn('sortorder', array(
          'header'    => Mage::helper('slider')->__('Sort Order'),
          'align'     =>'left',
          'index'     => 'sortorder',
      ));

      $this->addColumn('imagename', array(
          'header'    => Mage::helper('slider')->__('Image name'),
          'align'     =>'left',
          'index'     => 'imagename',
      ));

      $this->addColumn('description', array(
          'header'    => Mage::helper('slider')->__('Description'),
          'align'     =>'left',
          'index'     => 'description',
      ));

      $this->addColumn('slider_id', array(
          'header'    => Mage::helper('slider')->__('Slider ID'),
          'align'     => 'left',
          'index'     => 'slider_id',
          'type'      => 'options',
          'options'   => $this->getListSlider(),

      ));

      $this->addColumn('status', array(
          'header'    => Mage::helper('slider')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));


      $sliderId = $this->getRequest()->getParam('filtersliderid');
	  $linkRedirect = array('base' => '*/*/edit');
      if($sliderId != ''){
          $linkRedirect['base'] .= '/filtersliderid/' . $sliderId;
      }
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('slider')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('slider')->__('Edit'),
//                        'url'       => array('base'=> '*/*/edit/'),
                        'url' => $linkRedirect,
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('image_id');
        $this->getMassactionBlock()->setFormFieldName('imageslider');

        // for detete
        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('slider')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('slider')->__('Are you sure?')
        ));

        // for status
        $statuses = Mage::getSingleton('slider/status')->getOptionArray();
        /**
         * dump statuses
         * array(2) {
            [1]=>
            string(7) "Enabled"
            [2]=>
            string(8) "Disabled"
        }
         */

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('slider')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('slider')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));

        // HoangHH
        // for assign to slider
        $list = $this->getListSlider();
//        $list[0] = '(Not in any slider)';
        array_unshift($list,'(Not in any slider)');
//        array_unshift($list, array('label'=>'', 'value'=>''));
//        var_dump($list);
//        die();

        $this->getMassactionBlock()->addItem('setslider', array(
            'label'=> Mage::helper('slider')->__('Set slider id'),
            'url'  => $this->getUrl('*/*/massSetslider', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'setslider',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('slider')->__('Slider'),
                    'values' => $list,
                )
            )
        ));

        $this->getMassactionBlock()->addItem('changesortorder', array(
            'label'=> Mage::helper('slider')->__('Change sort order'),
            'url'  => $this->getUrl('*/*/massChangesortorder', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'changesortorder',
                    'type' => 'text',

                )
            )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
//      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

    // HoangHH
    public function getListSlider(){
        $sliderCollection = Mage::getModel('slider/slider')
            ->getCollection()
        ;
//        echo "<pre>";
//        var_dump($sliderCollection->getData());
        $resultArray = array();
        foreach ($sliderCollection as $slider){
//            $temp = array();
//            if($slider['status'] == 2){
//                continue;
//            }
//            $temp['label'] = $slider['title'];
//            $temp['value'] = $slider['slider_id'];
//            $resultArray[] = $temp;
            $resultArray[$slider['slider_id']] = $slider['title'];
        } // end foreach
        return $resultArray;
    } // end method getListSlider
} // end class
// end file