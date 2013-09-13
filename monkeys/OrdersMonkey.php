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

	public function getAddressesOfOrders($ordersAddressesIds){
		
		$addressesByIdsHashMap = array();
		$addressesByIdsArray;
		$addressesByIdsHashMapKey;

		$address = $this->myAdvancedManipulationEngine->retrieveData(
			'addresses', 
			NULL,
			array('id', 'address1', 'address2', 'postcode', 'city', 'company', 'lastname', 'firstname'), 
			array('id' => '[' . Utility::getOrPrestashopQueryStringFromArray($ordersAddressesIds) . ']')
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
			array('id' => '[' . Utility::getOrPrestashopQueryStringFromArray($idCartsArray) . ']')
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
		 	if ($totalShippingTaxExcluded = 0) 
		 		{ $portCode =  '\'\'';} 
		 		else 
		 	    {$portCode = '\'PRT\'';}
				
		if (
				/*Constants::existsInDB(
					OrdersConstants::GetCustomers($CodeClient),
					$this->sqlServerConnection
					) 
				&& */
				!Constants::existsInDB(
					OrdersConstants::GetOrders($orderID),
					$this->sqlServerConnection
					) 
			){

				$IdDocGestimum = odbc_exec(
											$this->sqlServerConnection,
											OrdersConstants::getExecG2GetNewNumeroProcedureString()
											) 
				or die ("<p>" . odbc_errormsg() . "</p>"); 
				
				$DocNumero = substr (str_repeat("0",10).odbc_result($IdDocGestimum,1),-10);
				odbc_close($this->sqlServerConnection);
				$constantsInstance = new Constants();
				$connect = odbc_connect(
										$constantsInstance->getSQLServerConnectionString(),
										$constantsInstance->getDataBaseUsername(),
										$constantsInstance->getDataBasePassword()
										);
				$IdDocPiece = odbc_exec(
										$connect,
										OrdersConstants::getExecG2GetNewPiece()
										)
				or die ("<p>" . odbc_errormsg() . "</p>");
				
				$DocPiece = odbc_result($IdDocPiece,1);
				odbc_close($connect);

				$lineNumber = 0;
				
				foreach ($currentOrder['orderRows'] as $key => $currentProduct) {
					
					$idProductAndIdProductAttribute = $currentProduct['product_id'] . $currentProduct['product_attribute_id'];
					
					$lineNumber += 16;
					
					$priceOfQuantity = $currentProduct['product_price'] * $currentProduct['product_quantity'];

					$productCount = OrdersConstants::GetProducts($idProductAndIdProductAttribute);
					$productExists = Constants::existsInDB($productCount, $this->sqlServerConnection);

					$queryForInsertingLines = OrdersConstants::getOrdersLinesInsertionString(
																						$DocNumero,
																						$lineNumber,
																						OrdersConstants::getValidProductId(
																							$idProductAndIdProductAttribute,
																							$productExists
																						),
																						Constants::upperString(OrdersConstants::getValidProductName(
																							$currentProduct['product_name'],
																							$productExists
																						)),
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

				$queryForInsertingDocuments = OrdersConstants::getOrdersDocumentsInsertionString(
																							$currentOrder['invoice_number'],
																							$currentOrder['invoice_date'],
																							$CodeClient,
																							$DocPiece,
																							Constants::upperString(OrdersConstants::getValidAddressRs(
																								$currentOrder['invoice_address']['firstname'],
																								$currentOrder['invoice_address']['lastname'],
																								$currentOrder['invoice_address']['company']
																							)),
																							Constants::upperString(OrdersConstants::getAddressRs2(
																								$currentOrder['invoice_address']['firstname'],
																								$currentOrder['invoice_address']['lastname'],
																								$currentOrder['invoice_address']['company']
																							)),
																							Constants::upperString($currentOrder['invoice_address']['address1']),
																							Constants::upperString($currentOrder['invoice_address']['address2']),
																							$currentOrder['invoice_address']['postcode'],
																							Constants::upperString($currentOrder['invoice_address']['city']),
																							Constants::upperString(OrdersConstants::getValidAddressRs(
																								$currentOrder['delivery_address']['firstname'],
																								$currentOrder['delivery_address']['lastname'],
																								$currentOrder['delivery_address']['company']
																							)),
																							Constants::upperString(OrdersConstants::getAddressRs2(
																								$currentOrder['delivery_address']['firstname'],
																								$currentOrder['delivery_address']['lastname'],
																								$currentOrder['delivery_address']['company']
																							)),
																							Constants::upperString($currentOrder['delivery_address']['address1']),
																							Constants::upperString($currentOrder['delivery_address']['address2']),
																							$currentOrder['delivery_address']['postcode'],
																							Constants::upperString($currentOrder['delivery_address']['city']),
																							$currentOrder['total_paid_tax_excl'],
																							$currentOrder['total_paid_tax_incl'],
																							$Tva,
																							$currentOrder['total_products_wt'],
																							$DocNumero,
																							$orderID,
																							$currentOrder['total_shipping_tax_excl'],
																							$currentOrder['carrier_tax_rate'],
																							$portCode
																							);
				
				odbc_exec(
						  $this->sqlServerConnection,
						  $queryForInsertingDocuments
						) 
				or die ("<p>" . odbc_errormsg() . "</p>");  
				
				odbc_close($this->sqlServerConnection); 
			}
		}
	}

	public function synchronizeAll(){
		
		$this->synchronizePrestashopToGestimum(
			$this->getOrdersFromPrestashop()
		);		
	}
}