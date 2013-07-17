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

	public function getOrPrestashopQueryStringFromArray($theArray){
		
		$idsQueryString = '';

		foreach ($theArray as $key => $value) {
			$idsQueryString = $idsQueryString . '|' . $value;
		}
		return trim($idsQueryString, '|');
	}

	public function getAddressesOfOrders($ordersAddressesIds){
		
		$addressesByIdsHashMap;
		$addressesByIdsArray;
		$addressesByIdsHashMapKey;

		$address = $this->myAdvancedManipulationEngine->retrieveData(
			'addresses', 
			NULL,
			array('id', 'address1', 'address2', 'postcode', 'city'), 
			array('id' => '[' . $this->getOrPrestashopQueryStringFromArray($ordersAddressesIds) . ']')
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

	public function getProductsOfCarts($idCartsArray){

		$productsByCartIdHashMap = array();
		$productsByCartIdHashMapKey;

		$productsArray = array();
		$idproduct;
		$associationsArray;

		$cart = $this->myAdvancedManipulationEngine->retrieveData(
			'carts',
			NULL,
			NULL,
			array('id' => '[' . $this->getOrPrestashopQueryStringFromArray($idCartsArray) . ']')
			);

		foreach ($cart as $key => $singleCart) {
			foreach ($singleCart as $cartKey => $cartValue) {
				switch ($cartKey) {
					case 'id':
						$productsByCartIdHashMapKey = $cartValue;
						//echo $cartValue . '<br/>';
						break;
					case 'associations':
						foreach (AdvancedManipulationEngine::getGrandChildren($cartValue) as $key => $value) {
							foreach ($value->children() as $associationKey => $associationValue) {
								switch ($associationKey) {
									case 'id_product':
										$idProduct = $associationValue;
										break;				
									default:
										$associationsArray[(string) $associationKey] = $associationValue;
										break;
								}
							}
							$productsArray[(string) $idProduct] = $associationsArray;
							$associationsArray = array();
						}
							break;
						default:
							break;
					}
				$productsByCartIdHashMap[(string) $productsByCartIdHashMapKey] = $productsArray;
				$productsArray = array();
			}
		}

		foreach ($productsByCartIdHashMap as $idCart => $productsArray) {
			echo 'id_cart : ' . $idCart . '<br/>';
			foreach ($productsArray as $productId => $productAttributes) {
				echo 'id_product : ' . $productId . '<br/>';
				foreach ($productAttributes as $attributeKey => $attributeValue) {
					echo $attributeKey . ' : ' . $attributeValue . '<br/>';
				}
			}
			echo "<br/>";
		}
	}

	public function getOrderDataArrays($order){

		$ordersDeliveryAddressesIds = array(); // getting the orders delivery addresses arrays

		$ordersInvoiceAddressesIds = array(); // getting the orders invoice addresses arrays

		$ordersCartAddressesIds = array(); // getting the orders carts


		foreach ($order as $key => $singleOrderAttributes) {
			foreach ($singleOrderAttributes as $key => $value) {
				switch ($key) {
					case 'id_address_delivery':
						$ordersDeliveryAddressesIds[] = $value;
						break;
					case 'id_address_invoice':
						$ordersInvoiceAddressesIds[] = $value;
						break;
					case 'id_cart':
						$ordersCartAddressesIds[] = $value;
						break;
					default:
						break;
				}
			}
		}
		$orderDataArrays['delivery_address'] = $ordersDeliveryAddressesIds;
		$orderDataArrays['invoice_address'] = $ordersInvoiceAddressesIds;
		$orderDataArrays['id_cart'] = $ordersCartAddressesIds;

		return $orderDataArrays;
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

		$orderDataArrays = $this->getOrderDataArrays($order);
		
		$deliveryAddressesOfOrders = $this->getAddressesOfOrders($orderDataArrays['delivery_address']);
		
		$invoiceAddressOfOrders = $this->getAddressesOfOrders($orderDataArrays['invoice_address']);

		$cartProductsArray = $this->getProductsOfCarts($orderDataArrays['id_cart']);

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
				case 'id_cart':
					//$ordersArray['order_products'] = $cartProductsArray[(string) $value];
					break;
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
		
		$this->getOrders();
		/*foreach ($this->getOrders() as $key => $value) {
			foreach ($value as $singleOrderkey => $singleOrdervalue) {
				switch ($singleOrderkey) {
					case 'delivery_address':
						echo "DELIVERY ADDRESS";
						foreach ($singleOrdervalue as $addressElementKey => $addressElementValue) {
							echo '|_'  . $addressElementKey . ' : ' . $addressElementValue . '<br/>';
						}
					case 'invoice_address':
						echo "INVOICE ADDRESS";
						foreach ($singleOrdervalue as $addressElementKey => $addressElementValue) {
							echo '|_'  . $addressElementKey . ' : ' . $addressElementValue . '<br/>';
						}
						break;
					case 'order_products':
						echo "ORDER PRODUCTS";
						/*foreach ($singleOrdervalue as $productKey => $productValue) {
							echo '|_' . $productKey . '<br/>';
							foreach ($productValue as $productAttributeKey => $productAttributeValue) {
								echo '|____' . $productAttributeKey . ' : ' . $productValue . '<br/>';
							}
						}
					default:
						echo $singleOrderkey . ' : ' . $singleOrdervalue . '<br/>';
						break;
				}
			}
		}*/
	}
}