<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category   Wdarking
 * @package    Wdarking_SGPweb
 * @author     Gilmar Pereira <wdarking@gmail.com>
 * @copyright  Copyright (c) 2019 Gilmar Pereira (wdarking@gmail.com)
 * @license    http://www.gnu.org/licenses/gpl.txt
 * @link       https://github.com/wdarking/Wdarking_SGPweb
 */

class Wdarking_SGPweb_Model_Sgp extends Varien_Object
{
    const FETCH_RATES_URL = 'https://www.sgpweb.com.br/novo/api/consulta-precos-prazos';
    const CREATE_SHIPMENT_URL = 'https://www.sgpweb.com.br/novo/api/pre-postagem';
    const FETCH_SHIPMENT_URL = 'https://www.sgpweb.com.br/novo/api/consulta-postagens';

    private $token;

    public function __construct(array $config)
    {
        if (isset($config['token'])) {
            $this->token = $config['token'];
        }
    }

    public function createShipment(array $data = [])
    {
        return $this->post(self::CREATE_SHIPMENT_URL, $data);
    }

    public function fetchRates(array $data = [])
    {
        return $this->post(self::FETCH_RATES_URL, $data);
    }

    public function post($url, $data)
    {
        if (!$this->token) {
            throw new \Exception("Wdarking_SGPweb_Model_Sgp::post undefined sgp token", 1);
        }

        if (!$url) {
            throw new \Exception("Wdarking_SGPweb_Model_Sgp::post undefined sgp url", 1);
        }

        $url = $url . "?chave_integracao={$this->token}";

        //Initiate cURL.
        $ch = curl_init($url);

        //The JSON data.
        $jsonData = json_encode($data);

        // 10 seconds start connection
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        // 10 seconds to execution
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);

        //Attach our encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        //Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //Execute the request
        $result = curl_exec($ch);

        if($result === false) {
            throw new \Exception("Wdarking_SGPweb_Model_Sgp::post curl error no: ".curl_errno($ch), 1);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode >= 300) {
            throw new \Exception("Wdarking_SGPweb_Model_Sgp::post unexpected response code {$httpCode}", 1);
        }

        curl_close($ch);

        return json_decode($result, true);
    }
}
