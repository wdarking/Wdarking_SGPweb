<?php
class Wdarking_SGPweb_Block_Adminhtml_Sgpplp_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("sgpweb_form", array("legend"=>Mage::helper("sgpweb")->__("Item information")));

						$fieldset->addField("track_id", "text", array(
						"label" => Mage::helper("sgpweb")->__("Track ID"),
						"name" => "track_id",
						));

						$fieldset->addField("increment_order_id", "text", array(
						"label" => Mage::helper("sgpweb")->__("Increment Order ID"),
						"name" => "increment_order_id",
						));


				if (Mage::getSingleton("adminhtml/session")->getSgpplpData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getSgpplpData());
					Mage::getSingleton("adminhtml/session")->setSgpplpData(null);
				}
				elseif(Mage::registry("sgpplp_data")) {
				    $form->setValues(Mage::registry("sgpplp_data")->getData());
				}
				return parent::_prepareForm();
		}
}
