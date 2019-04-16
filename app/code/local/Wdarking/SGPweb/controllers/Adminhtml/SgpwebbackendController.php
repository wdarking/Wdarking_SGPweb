<?php
class Wdarking_SGPweb_Adminhtml_SgpwebbackendController extends Mage_Adminhtml_Controller_Action
{

	protected function _isAllowed()
	{
		//return Mage::getSingleton('admin/session')->isAllowed('sgpweb/sgpwebbackend');
		return true;
	}

	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("SGPweb PLP"));
	   $this->renderLayout();
    }
}