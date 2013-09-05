<?php

class productsMonkey implements monkey{
	
	protected $myAdvancedManipulationEngine;

	protected $from;
	protected $to;

	protected $sqlServerConnection;

	protected $origin;
	
	function __construct($sqlServerConnection, $advancedManipulationEngine, $from, $to, $origin){
		$this->myAdvancedManipulationEngine = $advancedManipulationEngine;

		$this->from = $from;
		$this->to = $to;

		$this->sqlServerConnection = $sqlServerConnection;

		$this->origin = $origin;
	}

	public function getProductOptionsValuesNames(){
		
		$productOptionsValues = $this->myAdvancedManipulationEngine->retrieveData(
			'product_option_values',
			NULL,
			array('id','name'),
			NULL
			);

		$optionsValuesNamesByIds;

		$hashmapKey;
		$hashmapValue;
		
		foreach ($productOptionsValues as $productOptionsValueKey => $productOptionsValue) {
			foreach ($productOptionsValue as $nameKey => $nameValue) {
				switch ($nameKey) {
					case 'id':
						$hashmapKey = $nameValue;
						break;
					case 'name':
						foreach ($nameValue as $languageKey => $languageValue) {
							$hashmapValue = $languageValue; // I am sure that there are at most and at least 1 language value
						}
						break;
					default:
						break;
				}	
			}
			$optionsValuesNamesByIds[(int)$hashmapKey] = (string)$hashmapValue;
		}
		return $optionsValuesNamesByIds;
	}

	public function getProductsFromPrestashop(){

		$product = $this->myAdvancedManipulationEngine->retrieveData(
			'products',
			NULL,
			NULL,
			array('id' => '[' . $this->from . ',' . $this->to . ']')
			);

		$productsHashmap = array();
		$productsHashmapKey;
		$productsArray;

		$productOptionsValuesNames = $this->getProductOptionsValuesNames();
		foreach ($product as $key => $singleProductAttributes) {
			$categories = '';
			foreach ($singleProductAttributes as $key => $value) {

				switch ($key) {
					case  'id':
						$productsHashmapKey = $value;
						break;
					case 'id_supplier':
						$productsArray['id_supplier'] = $value;
						break;
					case 'id_manufacturer':
						$productsArray['id_manufacturer'] = $value;
						break;
					case 'id_category_default':
						$productsArray['id_category_default'] = $value;
						break;
					case 'id_shop_default':
						$productsArray['id_shop_default'] = $value;
						break;
					case 'id_tax_rules_group':
						$productsArray['id_tax_rules_group'] = $value;
						break;
					case 'on_sale':
						$productsArray['on_sale'] = $value;
						break;
					case 'online_only':
						$productsArray['online_only'] = $value;
						break;
					case 'ean13':
						$productsArray['ean13'] = $value;
						break;
					case 'upc':
						$productsArray['upc'] = $value;
						break;
					case 'ecotax':
						$productsArray['ecotax'] = $value;
						break;
					case 'quantity':
						$productsArray['quantity'] = $value;
						break;
					case 'minimal_quantity':
						$productsArray['minimal_quantity'] = $value;
						break;
					case 'price':
						$productsArray['price'] = $value;
						break;
					case 'wholesale_price':
						$productsArray['wholesale_price'] = $value;
						break;
					case 'unity':
						$productsArray['unity'] = $value;
						break;
					case 'unit_price_ratio':
						$productsArray['unit_price_ratio'] = $value;
						break;
					case 'additional_shipping_cost':
						$productsArray['additional_shipping_cost'] = $value;
						break;
					case 'reference':
						$productsArray['reference'] = $value;
						break;
					case 'supplier_reference':
						$productsArray['supplier_reference'] = $value;
						break;
					case 'location':
						$productsArray['location'] = $value;
						break;
					case 'width':
						$productsArray['width'] = $value;
						break;
					case 'height':
						$productsArray['height'] = $value;
						break;
					case 'depth':
						$productsArray['depth'] = $value;
						break;
					case 'weight':
						$productsArray['weight'] = $value;
						break;
					case 'out_of_stock':
						$productsArray['out_of_stock'] = $value;
						break;
					case 'quantity_discount':
						$productsArray['quantity_discount'] = $value;
						break;
					case 'customizable':
						$productsArray['customizable'] = $value;
						break;
					case 'uploadable_files':
						$productsArray['uploadable_files'] = $value;
						break;
					case 'text_fields':
						$productsArray['text_fields'] = $value;
						break;
					case 'active':
						$productsArray['active'] = $value;
						break;
					case 'available_for_order':
						$productsArray['available_for_order'] = $value;
						break;
					case 'available_date':
						$productsArray['available_date'] = $value;
						break;
					case 'condition':
						$productsArray['condition'] = $value;
						break;
					case 'show_price':
						$productsArray['show_price'] = $value;
						break;
					case 'indexed':
						$productsArray['indexed'] = $value;
						break;
					case 'visibility':
						$productsArray['visibility'] = $value;
						break;
					case 'cache_is_pack':
						$productsArray['cache_is_pack'] = $value;
						break;
					case 'cache_has_attachments':
						$productsArray['cache_has_attachments'] = $value;
						break;
					case 'is_virtual':
						$productsArray['is_virtual'] = $value;
						break;
					case 'cache_default_attribute':
						$productsArray['cache_default_attribute'] = $value;
						break;
					case 'date_add':
						$productsArray['date_add'] = $value;
						break;
					case 'date_upd':
						$productsArray['date_upd'] = $value;
						break;
					case 'advanced_stock_management':
						$productsArray['advanced_stock_management'] = $value;
						break;
					case 'name':
						foreach ($value as $languageKey => $languageValue) {
							$productsArray['name'] = $languageValue; // I am sure that there are at most and at least 1 language value
						}
					case 'associations':
						foreach ($value as $keyOfAssociation => $valueOfAssociation){
							if((string)$keyOfAssociation == 'product_option_values'){
								foreach ($valueOfAssociation as $keyOfProductOptionValues => $valueOfProductOptionValues) {
									foreach ($valueOfProductOptionValues as $productsOptionValuesId => $productsOptionValuesIdvalue) {
 										$productsArray['product_option_values'][(int)$productsOptionValuesIdvalue] = 
 											$productOptionsValuesNames[(int)$productsOptionValuesIdvalue];
									}
								}
							}elseif ((string)$keyOfAssociation == 'categories') {
								foreach ($valueOfAssociation as $keyOfCategories => $valueOfCategories) {
									foreach ($valueOfCategories as $keyOfCategory => $valueOfCategory) {
										if($keyOfCategory == 'id'){
											$categories .= ';' . $valueOfCategory;
										}
									}		 
								}
								$categories = trim($categories, ';');
								$productsArray['categories'] = $categories;
							}
						}
						break;
					default:
						break;
				}
			}
			$productsHashmap[(string)$productsHashmapKey] = $productsArray;
			$productsArray = array();
		}
		return $productsHashmap;
	}

	public function getProductsFromGestimum($forCreation, $limit, $gestimumProductId){
		
		$products = array();

		if($forCreation){
			$res = odbc_exec(
				$this->sqlServerConnection,
				ProductsConstants::getSelectProductsForCreationStoredProcedureCallString($this->origin, $limit, $gestimumProductId)
			)
			or die ("<p>" . odbc_errormsg() . "</p>");
		}else{
			$res = odbc_exec(
				$this->sqlServerConnection, 
				ProductsConstants::getSelectProductsForUpdateStoredProcedureCallString($this->origin, $limit, $gestimumProductId)
			)
			or die ("<p>" . odbc_errormsg() . "</p>");
		}

		while( $row = odbc_fetch_array($res) ) {
   	 		$products[] = $row;
		}
		return $products;
	}

	public function insertProductsIntoPrestashop($gestimumProducts = array()){
		
		$successfullyInsertedProducts = array();

		foreach ($gestimumProducts as $key => $product) {
			$productToInsertIntoPrestashop = array(
					  	  // Language
					  	  'name' => $product['name'],
					  	  //'description' => 'enjoy it bitch',
					  	  //'description_short' => 'c short',
					  	  'link_rewrite' => Utility::getProductLinkRewrite($product['name']),
					  	  //'meta_title' => 'title',
					  	  //'meta_description' => 'descr',
					  	  //'meta_keywords' => 'hodor,ggg,lol',
					  	  //'available_now' => 'hm yes',
					  	  //'available_later' => 'yes too',
					  	  // Simple
					  	  //'id_category_default' => '1',
					  	  'price' => Utility::getProductPrice(
											$product['PrixGrille'],
											$product['PrixPromo'],
											$product['PrixArticle'],
											$this->origin	
										),
					  	  'reference' => Utility::getProductReference(
											$product['reference_site_1'],
											$product['reference_site_2'],
											$this->origin	
										)
					  	  //'active' => '1',
					  	  //'available_for_order' => '1',
					  	  //'show_price' => '1',
					  	  // addChild
					  	  //'id_category' => '1'
							);
			if($product['width'] > 0){
				$productToInsertIntoPrestashop['width'] = $product['width'];
			}
			if($product['height'] > 0){
				$productToInsertIntoPrestashop['height'] = $product['height'];
			}
			if($product['ean13'] > 0){
				$productToInsertIntoPrestashop['ean13'] = $product['ean13'];
			}
			if($product['minimal_quantity'] > 0){
				$productToInsertIntoPrestashop['minimal_quantity'] = $product['minimal_quantity'];
			}
			if($this->myAdvancedManipulationEngine->createProduct($productToInsertIntoPrestashop)){
				$successfullyInsertedProducts[] = $product['id_product'];
			}
		}
		return $successfullyInsertedProducts;
	}

	public function updateProductsIntoPrestashop($gestimumProducts = array()){
		// TODO
		$successfullyUpdatedProducts = array();

		foreach ($gestimumProducts as $key => $product) {

			$productToUpdateIntoPrestashop = array(
					  	  // Language
					  	  //'name' => $product['name'],
					  	  //'description' => 'enjoy it bitch',
					  	  //'description_short' => 'c short',
					  	  'link_rewrite' => Utility::getProductLinkRewrite($product['name']),
					  	  //'meta_title' => 'title',
					  	  //'meta_description' => 'descr',
					  	  //'meta_keywords' => 'hodor,ggg,lol',
					  	  //'available_now' => 'hm yes',
					  	  //'available_later' => 'yes too',
					  	  // Simple
					  	  //'id_category_default' => '1',
					  	  'price' => Utility::getProductPrice(
											$product['PrixGrille'],
											$product['PrixPromo'],
											$product['PrixArticle'],
											$this->origin	
										),
					  	  'reference' => Utility::getProductReference(
											$product['reference_site_1'],
											$product['reference_site_2'],
											$this->origin	
										),
					  	  //'active' => '1',
					  	  //'available_for_order' => '1',
					  	  //'show_price' => '1',
					  	  // addChild
					  	  //'id_category' => '1'
					  	  'id' => $product['id_prestashop']
							);
			if($product['width'] > 0){
				$productToUpdateIntoPrestashop['width'] = $product['width'];
			}
			if($product['height'] > 0){
				$productToUpdateIntoPrestashop['height'] = $product['height'];
			}
			if($product['ean13'] > 0){
				$productToUpdateIntoPrestashop['ean13'] = $product['ean13'];
			}
			if($product['minimal_quantity'] > 0){
				$productToUpdateIntoPrestashop['minimal_quantity'] = $product['minimal_quantity'];
			}
			if($this->myAdvancedManipulationEngine->updateProduct($productToUpdateIntoPrestashop)){
				$successfullyUpdatedProducts[] = $product['reference'];
			}
		}
		return $successfullyUpdatedProducts;
	}

	public function updateGestimumProductLastSyncDate($successfullySynchronizedProducts){
		
		if(sizeof($successfullySynchronizedProducts)){
		
			return odbc_exec(
				$this->sqlServerConnection, 
				ProductsConstants::getUpdateProductLastSynchronizedString(
					ProductsConstants::generateSqlInClauseString(
						$successfullySynchronizedProducts
					), 
					$this->origin
				)
			);
		}
	}

	public function synchronizeGestimumToPrestashop($limit, $gestimumProductId){
		
		$successfullyInsertedProducts = $this->insertProductsIntoPrestashop(
			$this->getProductsFromGestimum(
					true, 
					$limit, 
					$gestimumProductId
				)
			);
		$this->updateGestimumProductLastSyncDate($successfullyInsertedProducts);
		/*
		$successfullyUpdatedProducts = $this->updateProductsIntoPrestashop(
			$this->getProductsFromGestimum(
					false, 
					$limit, 
					$gestimumProductId
				)
			);
		$this->updateGestimumProductLastSyncDate($successfullyUpdatedProducts);
		/*
		$this->myAdvancedManipulationEngine->updateData(
						array(
							  'id' => '1',
							  'lastname' => 'Vlodvek'
							  ),
						'customers'
					 );
		$successfullyUpdatedProducts = $this->updateProductsIntoPrestashop($this->getProductsFromGestimum(false));
		$this->updateGestimumProductLastSyncDate($successfullyUpdatedProducts);
		*/
	}

	public function synchronizePrestashopToGestimum(){

		// Retrieving the products with the variables $from, $to.
		$products = $this->getProductsFromPrestashop();
		
		// stored procedure fields.
		$idProduct = 'NULL';
		$idSupplier = 'NULL';
		$idManufacturer = 'NULL';
		$idManufacturer = 'NULL';
		$idCategoryDefault = '0';
		$idShopDefault = 'NULL';
		$idTaxRulesGroup = 'NULL';
		$onSale = 'NULL';
		$onlineOnly = 'NULL';
		$ean13 = 'NULL';
		$upc = 'NULL';
		$ecotax = 'NULL';
		$quantity = 'NULL';
		$minimalQuantit = '0';
		$price = 'NULL';
		$wholesalePrice = 'NULL';
		$unity = 'NULL';
		$unitPriceRatio = 'NULL';
		$additionalShippingCost = 'NULL';
		$reference = 'NULL';
		$supplierReference = 'NULL';
		$location = 'NULL';
		$width = '0';
		$height = '0';
		$depth = 'NULL';
		$weight = '0';
		$outOfStock = 'NULL';
		$quantityDiscount = 'NULL';
		$customizable = 'NULL';
		$uploadableFiles = 'NULL';
		$textFields = 'NULL';
		$active = 'NULL';
		$availableForOrder = 'NULL';
		$availableDate = 'NULL';
		$condition = 'NULL';
		$showPrice = 'NULL';
		$indexed = 'NULL';
		$visibility = 'NULL';
		$cacheIsPack = 'NULL';
		$cacheHasAttachments = 'NULL';
		$isVirtual = 'NULL';
		$cacheDefaultAttribute = 'NULL';
		$dateAdd = 'NULL';
		$dateUpd = NULL;
		$advancedStockManagement = 'NULL';
		$productOptionValues = array();
		$categories = 'NULL';

		foreach ($products as $idProduct => $productArray) {
			foreach ($productArray as $attribute => $value) {
				switch ($attribute) {
					case  'id':
						$idProduct = $value;
						break;
					case 'id_supplier':
						if($value) $idSupplier = $value;
						break;
					case 'id_manufacturer':
						if ($value) $idManufacturer = $value;
						break;
					case 'id_category_default':
						if( $value) $idCategoryDefault = $value;
						break;
					case 'id_shop_default':
						$idShopDefault = $value;
						break;
					case 'id_tax_rules_group':
						$idTaxRulesGroup = $value;
						break;
					case 'on_sale':
						$onSale = (int) $value;
						break;
					case 'online_only':
						$onlineOnly = (int) $value;
						break;
					case 'ean13':
						if($value) $ean13 = (string) $value;
						break;
					case 'upc':
						if($value) $upc = (string) $value;
						break;
					case 'ecotax':
						$ecotax = $value;
						break;
					case 'quantity':
						$quantity = $value;
						break;
					case 'minimal_quantity':
						$minimalQuantity = $value;
						break;
					case 'price':
						$price = (int)$value;
						break;
					case 'wholesale_price':
						$wholesalePrice = $value;
						break;
					case 'unity':
						if($value) $unity= (string) $value;
						break;
					case 'unit_price_ratio':
						$unitPriceRatio = $value;
						break;
					case 'additional_shipping_cost':
						if($value) $additionalShippingCost = $value;
						break;
					case 'reference':
						$reference = (string) $value;
						break;
					case 'supplier_reference':
						if($value) $supplierReference = $value;
						break;
					case 'location':
						if($value) $location = (string) $value;
						break;
					case 'width':
						$width = $value;
						break;
					case 'height':
						$height = $value;
						break;
					case 'depth':
						$depth = $value;
						break;
					case 'weight':
						$weight = $value;
						break;
					case 'out_of_stock':
						$outOfStock = $value;
						break;
					case 'quantity_discount':
						$quantityDiscount = (int) $value;
						break;
					case 'customizable':
						$customizable = (int) $value;
						break;
					case 'uploadable_files':
						$uploadableFiles = (int) $value;
						break;
					case 'text_fields':
						$textFields = (int) $value;
						break;
					case 'active':
						$active = (int) $value;
						break;
					case 'available_for_order':
						$availableForOrder = (int) $value;
						break;
					case 'available_date':
						$availableDate = (string) $value;
						break;
					case 'condition':
						$condition = (string) $value;
						break;
					case 'show_price':
						$showPrice = (int) $value;
						break;
					case 'indexed':
						$indexed = (int) $value;
						break;
					case 'visibility':
						$visibility = (string) $value;
						break;
					case 'cache_is_pack':
						$cacheIsPack = (int) $value;
						break;
					case 'cache_has_attachments':
						$cacheHasAttachments = (int) $value;
						break;
					case 'is_virtual':
						$isVirtual = (int) $value;
						break;
					case 'cache_default_attribute':
						if($value) $cacheDefaultAttribute = $value;
						break;
					case 'date_add':
						$dateAdd = (string) $value;
						break;
					case 'date_upd':
						$dateUpd = (string) $value;
						break;
					case 'advanced_stock_management':
						$advancedStockManagement = (int) $value;
						break;
					case 'product_option_values':
						$productOptionValues = $value;
						break;
					case 'categories':
						$categories = $value;
						break;
					default:
						break;
				}
			}

			if(sizeof($productOptionValues) == 0){
				// The product has no declension (option value)
				$productOptionValues[] = 'NULL';
			}
			$originInt = (int) $this->origin;
			
			$dateUpd = new DateTime($dateUpd);
			$dateUpd = $dateUpd->format('Y-m-d H:i:s');

			foreach ($productOptionValues as $key => $declension) {
				$IdDeclinaison = substr(strtoupper(str_replace(' ','',$key)),-5);
				$CodeArticle = $reference . $IdDeclinaison;
				
				if ($CodeArticle == '0') {
					throw new ProductWithoutReferenceException("Merci de donner une référence valide à votre produit", 1);
				}

				if (Constants::existsInDB(
					ProductsConstants::getSelectARTCODEString($this->origin, $idProduct),
					$this->sqlServerConnection
					)){
					odbc_exec($this->sqlServerConnection, ProductsConstants::getProductUpdatingString(
																						$productArray['name'],
 																						$declension,
 																						$minimalQuantit,
 																						$weight,
 																						$width,
 																						$height,
 																						$dateUpd,
						 																$IdDeclinaison,
																				 		$CodeArticle,
																				 		$this->origin,
																				 		$idProduct,
																				 		$categories)
					)or die ("<p>" . odbc_errormsg() . "</p>");	
				}
				else{	
					odbc_exec($this->sqlServerConnection, ProductsConstants::getProductInsertingString(
																						$CodeArticle,
																						$productArray['name'],
																						$declension,
																						$reference,
																						$minimalQuantit,
																						$weight,
																						$width,
																						$height,
																						$dateUpd,
																						$idCategoryDefault,
																						$idProduct,
																						$IdDeclinaison,
																						$this->origin,
																						$categories)
						) or die ("<p>" . odbc_errormsg() . "</p>");
				}
			}
		}
	}

	public function synchronizeAll(){
		//$this->synchronizePrestashopToGestimum();
		//$this->synchronizeGestimumToPrestashop();
	}
}

class ProductWithoutReferenceException extends Exception{} 