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

        $configValue = Mage::getStoreConfig('wdarking_sgpweb/wdk_sgpweb_config/wdk_sgpweb_remethods');

        Mage::log($configValue);
        if ($configValue) {
            $remaps = explode('|', trim($configValue));
            $remapped = [];
            foreach ($remaps as $remapString) {
                $methods = explode(',', $remapString);
                if (count($methods) !== 2) {
                    Mage::log('Invalid method string');
                    return false;
                }
                $remapped[$methods[0]] = $methods[1];
            }
            return $remapped;
        }

        return false;
    }

    public function parseSgpMethod($method)
    {
        $sgpRemapped = $this->getSgpRemapped();
        Mage::log($sgpRemapped);

        if (array_key_exists($method, $sgpRemapped)) {
            return $sgpRemapped[$method];
        }

        return false;
    }
}
