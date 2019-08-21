<?php


class Wdarking_SGPweb_Block_Adminhtml_Sgpplp extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_sgpplp";
	$this->_blockGroup = "sgpweb";
	$this->_headerText = Mage::helper("sgpweb")->__("Sgpplp Manager");
	$this->_addButtonLabel = Mage::helper("sgpweb")->__("Add New Item");
	parent::__construct();
	
	}

}