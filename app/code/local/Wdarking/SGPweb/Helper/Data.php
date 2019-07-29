<?php
class Wdarking_SGPweb_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isSgpEnabled() {

        $configValue = Mage::getStoreConfig('wdarking_sgpweb/wdk_sgpweb_config/wdk_sgpweb_enabled');

        if ($configValue) {
            return true;
        }

        return false;
    }

    public function getSgpToken() {

        $configValue = Mage::getStoreConfig('wdarking_sgpweb/wdk_sgpweb_config/wdk_sgpweb_webstoken');

        if ($configValue) {
            return $configValue;
        }

        return false;
    }

    public function getSgpRemapped() {

        $configValue = Mage::getStoreConfig('wdarking_sgpweb/wdk_sgpweb_config/wdk_sgpweb_remapshipping');

        if (strlen($configValue)) {
            $configValue = unserialize($configValue);
        }

        if (count($configValue)) {
            $remaps = $configValue;
            foreach ($remaps as $remap) {
                if (isset($remap['shipping_method'], $remap['sgp_method'])) {
                    $remapped[$remap['shipping_method']] = $remap['sgp_method'];
                }
            }
            return $remapped;
        }

        return false;
    }

    public function parseSgpMethod($method)
    {
        $sgpRemapped = $this->getSgpRemapped();

        if (array_key_exists($method, $sgpRemapped)) {
            return $sgpRemapped[$method];
        }

        return false;
    }
}
