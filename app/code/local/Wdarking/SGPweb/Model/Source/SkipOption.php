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

class Wdarking_SGPweb_Model_Source_SkipOption
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Constants for weight
     */
    const UNIQUE = 'unique';
    const INCLUDING = 'including';

    /**
     * Get options for weight
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::UNIQUE, 'label' => Mage::helper('adminhtml')->__('Unique Item on Order')),
            array('value' => self::INCLUDING, 'label' => Mage::helper('adminhtml')->__('Including Item on Order')),
        );
    }

    public function getAllOptions()
    {
        return self::toOptionArray();
    }
}
