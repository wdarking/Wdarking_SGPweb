<?php
class Wdarking_SGPweb_Model_Observer
{
	public function salesOrderShipmentSaveBefore(Varien_Event_Observer $observer)
	{
        $helper = Mage::helper('sgpweb');

        if (!$helper->isSgpEnabled()) {
            return;
        }

        $shipment = $observer->getShipment();

        $order = $shipment->getOrder();

        $observerFlag = "wdk_sgp_order_shipment_flag:{$order->getId()}";

        if (Mage::registry($observerFlag)) {
            return;
        }

        Mage::register($observerFlag, 1);

        $shippingAddress = $order->getShippingAddress();
        $shippingMethod = $order->getShippingMethod();

        $sgpMethod = $helper->parseSgpMethod($shippingMethod);

        if (!$sgpMethod) {
            Mage::log("{$shippingMethod} not in sgp remapped methods");
            return;
        }

        $data = [
            'destinatario' => $shippingAddress->getName(),
            'endereco' => $shippingAddress->getStreet(1),
            'numero' => $shippingAddress->getStreet(2),
            'bairro' => $shippingAddress->getStreet(3),
            'complemento' => $shippingAddress->getStreet(4),
            'cep' => $shippingAddress->getPostcode(),
            'email' => $shippingAddress->getEmail(),
            'servico_correios' => $sgpMethod,
            'tipo' => 1, // pacote
            'monitorar' => 1
        ];

        if ($phone = $shippingAddress->getTelephone()) {
            $data['telefone'] = $phone;
        }

        Mage::log($data);

        $sgpToken = $helper->getSgpToken();

        $response = null;
        try {
            $response = $this->post($data, $sgpToken);
        } catch (Exception $e) {
            Mage::log($e->getMessage());
            Mage::getSingleton('core/session')->addNotice('Problema na requisição para o SGPweb: ' . $e->getMessage());
        }

        if ($response) {
            $responseObject = json_decode($response);
            Mage::log($responseObject);

            if (!$responseObject || !$responseObject->retorno) {
                Mage::getSingleton('core/session')->addNotice('Problema no retorno do SGPweb: sem `retorno`');
                return;
            }

            $data = $responseObject->retorno;

            if (!$data->objetos || count($data->objetos) < 1) {
                $message = "Problema no retorno do SGPweb: ";
                $message .= "[ {$data->status_processamento} ] {$data->status}";
                Mage::getSingleton('core/session')->addNotice($message);
                return;
            }

            $sgpObject = $data->objetos[0];

            if (!$sgpObject->objeto) {
                Mage::getSingleton('core/session')->addNotice('Problema no retorno do SGPweb: objeto sem rastreio');
                return;
            }

            $track = Mage::getModel('sales/order_shipment_track')
                ->setNumber($sgpObject->objeto)
                ->setCarrierCode($shippingMethod)
                ->setTitle("SGPweb rastreio para {$sgpObject->servico_correios} - {$shippingMethod}");

            $shipment->addTrack($track);
        }
	}

    public function post($data, $token)
    {
        if (!$token) {
            throw new \Exception("Error Processing Request: undefined sgp token", 1);
        }

        $url = "https://www.sgpweb.com.br/novo/api/pre-postagem?chave_integracao={$token}";
        Mage::log($url);

        //Initiate cURL.
        $ch = curl_init($url);

        //The JSON data.
        $jsonData = json_encode($data);

        //Encode the array into JSON.
        $jsonDataEncoded = "{ \"objetos\": [{$jsonData}] }";

        Mage::log($jsonDataEncoded);

        // 10 seconds start connection
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        // 10 seconds to execution
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);

        //Attach our encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

        //Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //Execute the request
        $result = curl_exec($ch);

        Mage::log($result);

        if($result === false) {
            throw new \Exception("Error Processing Request: ".curl_errno($ch), 1);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode >= 300) {
            throw new \Exception("Error Processing Request: unexpected response code {$httpCode}", 1);
        }

        curl_close($ch);

        return $result;
    }
}
