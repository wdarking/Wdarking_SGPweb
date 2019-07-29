<?php
/**
 * @author Wdarking <wdarking@gmail.com>
 */
class Wdarking_SGPweb_Block_Config_Form_Field_RemapShipping extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    /**
     * Initialize custom block options to
     * set form table options on module config
     */
    public function __construct()
    {
        $this->addColumn('shipping_method', array(
            'label' => Mage::helper('adminhtml')->__('Shipping Method'),
            'style' => 'width:120px'
        ));

        $this->addColumn('sgp_method', array(
            'label' => Mage::helper('adminhtml')->__('SGP Method'),
            'style' => 'width:200px'
        ));

        $this->_addAfter       = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add option');

        parent::__construct();
    }
}

?>
