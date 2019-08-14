<?php

/**
 * Magento Minerva Shipping Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Minerva
 * @package    Minerva_Shipping
 * @subpackage Multiple Flatrate
 * @copyright  Copyright (c) 2008 Sherrie Rohde (http://www.sherrierohde.com)
 * @author     Sherrie Rohde (sherrie.rohde@gmail.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */







class Minerva_Shipping_Model_Carrier_Multiflat extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{ 
    protected $_code = 'msmultiflat';

    /**
     * Enter description here...
     *
     * @param Mage_Shipping_Model_Rate_Request $data
     * @return Mage_Shipping_Model_Rate_Result
     */

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }
        $result = Mage::getModel('shipping/rate_result');
		$packageValue = $request->getBaseCurrency()->convert($request->getPackageValue(), $request->getPackageCurrency());
        $allow = ($request->getmultiflat())
            || ($packageValue >= $this->getConfigData('free_shipping_subtotal'));
        for($i = 0; $i <= 10; $i++)
        {
            $shippingPrice = $this->getConfigData('price'.$i);
			$shippingName = $this->getConfigData('name'.$i);
            if($shippingName != "")
            {                
                $method = Mage::getModel('shipping/rate_result_method');
                $method->setCarrier('msmultiflat');
                $method->setCarrierTitle($this->getConfigData('title'));
                $method->setMethod($this->getConfigData('name'.$i)); 
                $method->setMethodTitle($this->getConfigData('name'.$i));
				$method->setMethodDetails($this->getConfigData('details'.$i));
				$method->setMethodDescription($this->getConfigData('details'.$i));
                $method->setPrice($shippingPrice);
                $method->setCost($shippingPrice);
                $result->append($method);
            }

			else if ($shippingName == "")
            {                
            }

        }

        return $result; 
    }

    public function getAllowedMethods()
    {
        return array('msmultiflat'=>$this->getConfigData('name'));
    }
}
?>