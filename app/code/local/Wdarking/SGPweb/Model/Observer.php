<?php
class Wdarking_SGPweb_Model_Observer
{
	public function salesOrderShipmentSaveBefore(Varien_Event_Observer $observer)
	{
        if (!$this->helper()->isSgpEnabled()) {
            return;
        }

        $shipment = $observer->getShipment();

        if ($shipment->isObjectNew()) {

            $plp = Mage::getModel('sgpweb/sgpplp');

            $order = $shipment->getOrder();

            if ($this->helper()->skipCreateShipment($order)) {
                Mage::log("Shipment request skiped");
                return;
            }

            $plp->setOrderId($shipment->getOrderId());
            $plp->setIncrementOrderId($order->getIncrementId());

            $plp->setShippingCarrier($order->getShippingMethod(true)->getCarrierCode());
            $plp->setShippingMethod($order->getShippingMethod(true)->getMethod());

            $plp->setReceiverName($order->getShippingAddress()->getName());
            $plp->setReceiverAddress($order->getShippingAddress()->getStreetFull());

            if ($response = $this->createShipment($order)) {
                if (isset($response['retorno'], $response['retorno']['objetos'], $response['retorno']['objetos'][0])) {
                    $object = $response['retorno']['objetos'][0];
                    if (isset($object['objeto'])) {
                        $plp->setTrackId($object['objeto']);
                        $plp->setSgpService($this->helper()->getSgpMethodTitle($object['servico_correios']));
                    } else {
                        Mage::throwException(Mage::helper('adminhtml')->__('Problema ao gerar rastreio no SGPweb: ' . $object['erros']));
                    }
                }
            }

            $plp->setCreatedAt(time());
            $plp->setUpdatedAt(time());
            $plp->save();

            if ($trackId = $plp->getTrackId()) {
                $track = Mage::getModel('sales/order_shipment_track')
                    ->setNumber($trackId)
                    ->setCarrierCode($plp->getShippingCarrier())
                    ->setTitle("SGPweb: via {$plp->getShippingMethod()}");

                $shipment->addTrack($track);

                $shipment->sendEmail();
            }
        }
	}

    public function createShipment($order)
    {
        Mage::log("Wdarking_SGPweb_Model_Observer::createShipment");

        $sgpMethod = $this->getSgpMethod($order->getShippingMethod(true));

        if (!$sgpMethod) {
            return false;
        }

        $data = ['objetos' => []];

        $object = [
            'destinatario' => $order->getShippingAddress()->getName(),
            'endereco' => $order->getShippingAddress()->getStreet(1),
            'numero' => $order->getShippingAddress()->getStreet(2),
            'bairro' => $order->getShippingAddress()->getStreet(3),
            'complemento' => $order->getShippingAddress()->getStreet(4),
            'cep' => $order->getShippingAddress()->getPostcode(),
            'email' => $order->getShippingAddress()->getEmail(),
            'servico_correios' => $sgpMethod,
            'tipo' => 1, // pacote
            'monitorar' => 1
        ];

        if ($phone = $order->getShippingAddress()->getTelephone()) {
            $object['telefone'] = $phone;
        }

        array_push($data['objetos'], $object);

        try {

            $sgp = new Wdarking_SGPweb_Model_Sgp(['token' => $this->helper()->getSgpToken()]);

            Mage::log($data);

            $response = $sgp->createShipment($data);

            Mage::log($response);

        } catch (Exception $e) {
            Mage::log($e->getMessage());
            Mage::getSingleton('core/session')->addError('Problema na requisição para o SGPweb: ' . $e->getMessage());
        }

        return $response;
    }

    public function getSgpMethod(Varien_Object $shipment)
    {
        Mage::log("Wdarking_SGPweb_Model_Observer::getSgpMethod");

        $method = $shipment->getMethod();

        Mage::log($method);

        if (strpos($method, 'sgpweb_') > -1) {
            return explode('_', $method)[1];
        }

        return $this->helper()->parseSgpMethod($shipment->getMethod());
    }

    public function helper()
    {
        return Mage::helper('sgpweb');
    }
}
