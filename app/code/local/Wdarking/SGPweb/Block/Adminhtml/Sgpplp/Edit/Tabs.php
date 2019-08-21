<?php
class Wdarking_SGPweb_Block_Adminhtml_Sgpplp_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("sgpplp_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("sgpweb")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("sgpweb")->__("Item Information"),
				"title" => Mage::helper("sgpweb")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("sgpweb/adminhtml_sgpplp_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
