<?php

class OrdersMonkey implements monkey{
	
	protected $myAdvancedManipulationEngine;

	protected $from;
	protected $to;

	function __construct($advancedManipulationEngine, $from, $to){
		$this->myAdvancedManipulationEngine = $advancedManipulationEngine;

		$this->from = $from;
		$this->to = $to;
	}

	public function getAddressesOfOrders($ordersAddressesIds){
		$addressesByIdsHashMap;
		$addressesByIdsArray;
		$addressesByIdsHashMapKey;
		$idsQueryString = '';
		foreach ($ordersAddressesIds as $key => $value) {
			$idsQueryString = $idsQueryString . '|' . $value;
		}
		$idsQueryString = trim($idsQueryString, '|');

		$address = $this->myAdvancedManipulationEngine->retrieveData(
			'addresses', 
			NULL,
			array('id', 'address1', 'address2', 'postcode'), 
			array('id' => '[' . $idsQueryString . ']')
			);

		foreach ($address as $key => $values) {
			foreach ($values as $key => $value) {
				switch ($key) {
					case 'id':
						$addressesByIdsHashMapKey = $value;
					break;
					default:
						$addressesByIdsArray[(string) $key] = $value;
					break;
				}
			}
			$addressesByIdsHashMap[(string) $addressesByIdsHashMapKey] = $addressesByIdsArray;
		}
		return $addressesByIdsHashMap;
	}

	public function getOrders(){

		$order = $this->myAdvancedManipulationEngine->retrieveData(
			'orders',
			NULL,
			NULL,
			array('id' => '[' . $this->from . ',' . $this->to . ']')
			);

		$ordersHashmap;
		$ordersHashmapKey;
		$ordersArray;

		$ordersDeliveryAddressesIds = array(); // getting the orders delivery addresses arrays

		$ordersInvoiceAddressesIds = array(); // getting the orders invoice addresses arrays

		foreach ($order as $key => $singleOrderAttributes) {
			foreach ($singleOrderAttributes as $key => $value) {
				switch ($key) {
					case 'id_address_delivery':
						$ordersDeliveryAddressesIds[] = $value;
						break;
					case 'id_address_invoice':
						$ordersInvoiceAddressesIds[] = $value;
						break;
					default:
						break;
				}
			}
		}
		
		$deliveryAddressesOfOrders = $this->getAddressesOfOrders($ordersDeliveryAddressesIds);
		
		$invoiceAddressOfOrders = $this->getAddressesOfOrders($ordersInvoiceAddressesIds);

		foreach ($order as $key => $singleOrderAttributes) {
			foreach ($singleOrderAttributes as $key => $value) {
				switch($key){
					case 'id':
					$ordersHashmapKey = $value;
					break;
				case 'id_address_delivery':
					$ordersArray['delivery_address'] = $deliveryAddressesOfOrders[(string) $value];
					break;
				case 'id_address_invoice':
					$ordersArray['invoice_address'] = $invoiceAddressOfOrders[(string) $value];
				default:
					$ordersArray[(string)$key] = $value;
					break;
				}
			}
			$ordersHashmap[(string) $ordersHashmapKey] = $ordersArray;
			$ordersArray = array(); // reinitializing the array
		}
		return $ordersHashmap;
	}

	public function synchronizeAll(){
		
		/*foreach ($this->getOrders() as $key => $value) {
			foreach ($value as $singleOrderkey => $singleOrdervalue) {
				switch ($singleOrderkey) {
					case 'delivery_address':
						echo "DELIVERY ADDRESS";
						foreach ($singleOrdervalue as $addressElementKey => $addressElementValue) {
							echo '|_'  . $addressElementValue . '<br/>';
						}
					case 'invoice_address':
						echo "INVOICE ADDRESS";
						foreach ($singleOrdervalue as $addressElementKey => $addressElementValue) {
							echo '|_'  . $addressElementKey . ' : ' . $addressElementValue . '<br/>';
						}
						break;
					default:
						echo $singleOrderkey . ' : ' . $singleOrdervalue . '<br/>';
						break;
				}
			}
		}*/
	}
}