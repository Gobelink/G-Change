<?php

class OrdersMonkey implements monkey{

	protected $myAdvancedManipulationEngine;
	protected $sqlServerConnection;
	protected $from;
	protected $to;

	function __construct($sqlServerConnection, $advancedManipulationEngine, $from, $to){
		$this->myAdvancedManipulationEngine = $advancedManipulationEngine;
	
		$this->sqlServerConnection = $sqlServerConnection;
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
		
		$addressesByIdsHashMap = array();
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

	public function getOrderRows($orderAssociationsAttribute){

		$orderRows = AdvancedManipulationEngine::getGrandChildren($orderAssociationsAttribute);
		$productArray;
		$orderRowsArray = array();

		foreach ($orderRows as $key => $singleOrderRow) {
			foreach ($singleOrderRow as $singleOrderRowKey => $singleOrderRowValue) {
				$productArray[$singleOrderRowKey] = $singleOrderRowValue;
			}	
			$orderRowsArray[] = $productArray;
			$productArray = array(); // Making sure that no product attribute ends up in an another product
		}
		return $orderRowsArray;
	}

	public function getOrdersFromPrestashop(){

		$order = $this->myAdvancedManipulationEngine->retrieveData(
			'orders',
			NULL,
			NULL,
			array('id' => '[' . $this->from . ',' . $this->to . ']')
			);

		$ordersHashmap = array();
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
					$ordersArray['order_products'] = $cartProductsArray[(string) $value];
					break;
				case 'associations':
					$ordersArray['orderRows'] = $this->getOrderRows($value);
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

	public function synchronizePrestashopToGestimum($prestashopOrders){
		 
		foreach ($prestashopOrders as $orderID => $currentOrder){
			//$CodeClient = '\'W\'+'.$currentOrder['id_customer'];
		 	$CodeClient = '\'W'.$currentOrder['id_customer'].'\'';
		 	$Tva = $currentOrder['total_paid_tax_incl'] - $currentOrder['total_paid_tax_excl'];
		
			$IdDocGestimum = odbc_exec(
										$this->sqlServerConnection,
										Constants::getExecG2GetNewNumeroProcedureString()
										) 
			or die ("<p>" . odbc_errormsg() . "</p>"); 
			
			$DocNumero = substr (str_repeat("0",10).odbc_result($IdDocGestimum,1),-10);
			odbc_close($this->sqlServerConnection);
			
			$connect = odbc_connect(
									Constants::getSQLServerConnectionString(),
									Constants::getDataBaseUsername(),
									Constants::getDataBasePassword()
									);
			$IdDocPiece = odbc_exec(
									$connect,
									Constants::getExecG2GetNewPiece()
									)
			or die ("<p>" . odbc_errormsg() . "</p>");
			
			$DocPiece = odbc_result($IdDocPiece,1);
			odbc_close($connect);

			$i = 0;
			
			foreach ($currentOrder['orderRows'] as $key => $currentProduct) {
				$i+= 16;
				$priceOfQuantity = $currentProduct['product_price'] * $currentProduct['product_quantity'];
				
				$queryForInsertingLines = Constants::getOrdersLinesInsertionString(
																					$DocNumero,
																					$i,
																					$currentProduct['product_attribute_id'],
																					$currentProduct['product_name'],
																					$currentProduct['product_quantity'],
																					$currentProduct['product_price'],
																					$currentOrder['invoice_date'],
																					$priceOfQuantity,
																					$DocPiece
																					);
				odbc_exec($this->sqlServerConnection,$queryForInsertingLines)
					or die ("<p>" . odbc_errormsg() . "</p>");
				odbc_close($this->sqlServerConnection); 
			}

			$queryForInsertingDocuments = Constants::getOrdersDocumentsInsertionString(
																						$currentOrder['invoice_number'],
																						$currentOrder['invoice_date'],
																						$CodeClient,
																						$DocPiece,
																						$currentOrder['invoice_address']['address1'],
																						$currentOrder['invoice_address']['postcode'],
																						$currentOrder['invoice_address']['city'],
																						$currentOrder['delivery_address']['address1'],
																						$currentOrder['delivery_address']['postcode'],
																						$currentOrder['delivery_address']['city'],
																						$currentOrder['total_paid_tax_excl'],
																						$currentOrder['total_paid_tax_incl'],
																						$Tva,
																						$currentOrder['total_products_wt'],
																						$DocNumero
																						);
			
			odbc_exec(
					  $this->sqlServerConnection,
					  $queryForInsertingDocuments
					) 
			or die ("<p>" . odbc_errormsg() . "</p>");  
			
			odbc_close($this->sqlServerConnection); 
		}
	}

	public function synchronizeAll(){
		
		$this->synchronizePrestashopToGestimum(
			$this->getOrdersFromPrestashop()
		);		
	}
}