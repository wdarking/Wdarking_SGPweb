<?php
	
class Wdarking_SGPweb_Block_Adminhtml_Sgpplp_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "plp_id";
				$this->_blockGroup = "sgpweb";
				$this->_controller = "adminhtml_sgpplp";
				$this->_updateButton("save", "label", Mage::helper("sgpweb")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("sgpweb")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("sgpweb")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("sgpplp_data") && Mage::registry("sgpplp_data")->getId() ){

				    return Mage::helper("sgpweb")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("sgpplp_data")->getId()));

				} 
				else{

				     return Mage::helper("sgpweb")->__("Add Item");

				}
		}
}