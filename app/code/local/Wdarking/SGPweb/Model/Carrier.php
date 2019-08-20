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

class Wdarking_SGPweb_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'wdarking_sgpweb';

    public function isTrackingAvailable()
    {
        return true;
    }

    public function getAllowedMethods()
    {
        $methods = array();
        $options = Mage::getSingleton('sgpweb/source_carrierMethods')->getAllOptions();
        foreach ($options as $option) {
            $methods["{$this->getCarrierCode()}_{$option['value']}"] = $option['label'];
        }
        return $methods;
    }

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        Mage::log('Wdarking_SGPweb_Model_Carrier::collectRates');

        $result = Mage::getModel('shipping/rate_result');

        $methods = $this->fetchSGPrates($request);

        if (! (count($methods) > 0)) {
            $rate = Mage::getModel('shipping/rate_result_error');
            $rate->setCarrier($this->getCarrierCode());
            $rate->setErrorMessage("SGPweb was is unavailable currently. Check logs.");
            $result->append($rate);
        } else {
            foreach ($methods as $method) {
                $rate = Mage::getModel('shipping/rate_result_method');
                $rate->setCarrier($this->getCarrierCode());
                $rate->setCarrierTitle($this->getConfigData('title'));
                $rate->setMethod("{$this->getCarrierCode()}_{$method['Codigo']}");
                $rate->setMethodTitle($this->helper()->getSgpMethodTitle($method));
                $rate->setPrice($this->getFinalPriceWithHandlingFee(str_replace(',', '.', $method['Valor'])));
                $rate->setCost(str_replace(',', '.', $method['Valor']));
                $result->append($rate);
            }
        }

        return $result;
    }

    public function fetchSGPrates($request)
    {
        Mage::log('Wdarking_SGPweb_Model_Carrier::fetchSGPrates');

        $sgp = new Wdarking_SGPweb_Model_Sgp(['token' => $this->helper()->getSgpToken()]);

        try {
            $result = $sgp->fetchRates([
                'cep_origem' => Mage::getStoreConfig('shipping/origin/postcode', $this->getStore()),
                'cep_destino' => $request->getDestPostcode(),
                'peso' => $request->getPackageWeight() / 1000,
                'mao_propria' => 'N',
                'aviso_recebimento' => 'N',
                'servicos' => explode(',', $this->getConfigData('allowed_methods'))
            ]);
        } catch (Exception $e) {
            Mage::log('ERROR: ' . $e->getMessage());
            return [];
        }

        if (isset($result['servicos'])) {
            return $result['servicos'];
        }

        return [];
    }

    /**
     * Get Tracking Info
     *
     * @param mixed $trackingCodes Tracking
     *
     * @return mixed
     */
    public function getTrackingInfo($trackingCodes)
    {
        // $result = Mage::getModel('shipping/tracking_result');
        // foreach ((array) $trackingCodes as $code) {
        //     $error = Mage::getModel('shipping/tracking_result_error');
        //     $error->setTracking($code);
        //     $error->setCarrier($this->getCarrierCode());
        //     $error->setCarrierTitle($this->getConfigData('title'));

        //     list($nf, $nfSerie) = explode('-', $code);
        //     $params = new TntMercurio_LocalizacaoIn();
        //     $params->nf = (int)$nf;
        //     $params->nfSerie = (int)$nfSerie;
        //     $params->cnpj = (string)$this->getConfigData('api_vat');
        //     $params->usuario = $this->getConfigData('api_login');
        //     $request = new TntMercurio_Localizacao(array(), $this->getConfigData('url_tracking'));

        //     try {
        //         $response = $request->localizaMercadoria(new TntMercurio_LocalizaMercadoria($params));
        //         Mage::log(print_r($response, true));
        //         $err = (array)$response->out->erros;
        //         if (!empty($err)) {
        //             throw new Exception(print_r($err, true));
        //         }
        //     } catch (Exception $e) {
        //         $error->setErrorMessage($e->getMessage());
        //         $result->append($error);
        //         continue;
        //     }

        //     $progress = $this->_getTrackingProgress($response);
        //     if (!empty($progress)) {
        //         $track = array_pop($progress);
        //         $track['progressdetail'] = $progress;
        //         $status = Mage::getModel('shipping/tracking_result_status');
        //         $status->setTracking($code);
        //         $status->setCarrier($this->getCarrierCode());
        //         $status->setCarrierTitle($this->getConfigData('title'));
        //         $status->addData($track);
        //         $result->append($status);
        //         continue;
        //     } else {
        //         $result->append($error);
        //         continue;
        //     }
        // }

        // if ($trackings = $result->getAllTrackings()) {
        //     return $trackings[0];
        // }

        return false;
    }

    public function helper()
    {
        return Mage::helper('sgpweb');
    }

}
