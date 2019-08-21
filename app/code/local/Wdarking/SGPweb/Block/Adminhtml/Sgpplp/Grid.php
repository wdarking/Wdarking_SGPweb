<?php

class Wdarking_SGPweb_Block_Adminhtml_Sgpplp_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("sgpplpGrid");
				$this->setDefaultSort("plp_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("sgpweb/sgpplp")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("plp_id", array(
				"header" => Mage::helper("sgpweb")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "plp_id",
				));

				$this->addColumn("incerement_order_id", array(
				"header" => Mage::helper("sgpweb")->__("Order ID"),
				"index" => "incerement_order_id",
				));

				$this->addColumn("track_id", array(
				"header" => Mage::helper("sgpweb")->__("Track ID"),
				"index" => "track_id",
				));

				$this->addColumn("shipping_carrier", array(
				"header" => Mage::helper("sgpweb")->__("Shipping Carrier"),
				"index" => "shipping_carrier",
				));

				$this->addColumn("shipping_method", array(
				"header" => Mage::helper("sgpweb")->__("Shipping Method"),
				"index" => "shipping_method",
				));

				$this->addColumn("receiver_name", array(
				"header" => Mage::helper("sgpweb")->__("Receiver Name"),
				"index" => "receiver_name",
				));

				$this->addColumn("receiver_address", array(
				"header" => Mage::helper("sgpweb")->__("Receiver Address"),
				"index" => "receiver_address",
				));

				$this->addColumn("movimentations", array(
				"header" => Mage::helper("sgpweb")->__("Movimentations"),
				"index" => "movimentations",
				));

				$this->addColumn("created_at", array(
				"header" => Mage::helper("sgpweb")->__("Created At"),
				"index" => "created_at",
				));

				$this->addColumn("updated_at", array(
				"header" => Mage::helper("sgpweb")->__("Updated At"),
				"index" => "updated_at",
				));

			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}



		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('plp_id');
			$this->getMassactionBlock()->setFormFieldName('plp_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_sgpplp', array(
					 'label'=> Mage::helper('sgpweb')->__('Remove Sgpplp'),
					 'url'  => $this->getUrl('*/adminhtml_sgpplp/massRemove'),
					 'confirm' => Mage::helper('sgpweb')->__('Are you sure?')
				));
			return $this;
		}


}
