<?php
class Wdarking_SGPweb_PlpsController extends Mage_Core_Controller_Front_Action
{
    public function indexAction() {
        $plp = Mage::getModel('sgpweb/sgpplp');
        $plp->setTrackId("JN123123123");
        $plp->setOrderId("100032423");
        $plp->setCreatedAt(time());
        $plp->setUpdatedAt(time());
        $plp->save();
        var_dump($plp->toArray());
    }
}
