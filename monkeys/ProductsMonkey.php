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

		$productsHashmap;
		$productsHashmapKey;
		$productsArray;

		$productOptionsValuesNames = $this->getProductOptionsValuesNames();
		foreach ($product as $key => $singleProductAttributes) {
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
				
				$verif = odbc_exec($this->sqlServerConnection,'SELECT A.ART_CODE FROM ARTICLES A WHERE A.ART_CODE = \''. $CodeArticle .'\'');
				$result = odbc_result($verif,1);

				if (!empty($result)){
					odbc_exec($this->sqlServerConnection,'UPDATE ARTICLES SET '
													  . ' [ART_LIB] = \'' . preg_replace('/\'/','\'\'', $productArray['name']) . ' ' . preg_replace('/\'/','\'\'', $declension) . '\''
												      . ' ,[ART_LIBC] =  \'' . preg_replace('/\'/','\'\'', $reference) . '\''
													  . ' ,[ART_QTEDFT] = ' . $minimalQuantit
													  . ' ,[ART_POIDSB] = ' . $weight
												      . ' ,[ART_POIDST] = 0' 
												      . ' ,[ART_POIDSN] = ' . $weight
												      . ' ,[ART_LONG] = ' . $width
												      . ' ,[ART_LARG] = '. $height
													  . ' ,[ART_DORT] = 0'
													  . ' ,[ART_P_ACH] = 0'
													  . ' ,[ART_P_PRV] = 0'
													  . ' ,[ART_P_COEF] = 0'
													  . ' ,[ART_P_VTEB] = 0'
													  . ' ,[ART_P_VTE] = 0'
													  . ' ,[ART_P_EURO] = 0'
													  . ' ,[ART_DTMAJ] = \'' . Utility::getNoZeroDate($dateUpd) . '\''
													  . ' ,[ART_USRMAJ] = \'WEB\''
													  . ' ,[ART_NUMMAJ] = [ART_NUMMAJ]+1 '
													  . 'WHERE [ART_CODE] = \'' . $CodeArticle . '\''
													  ) 
					or die ("<p>" . odbc_errormsg() . "</p>");	
				}				
				else{	
					odbc_exec($this->sqlServerConnection,'INSERT INTO dbo.ARTICLES
													   (ART_CODE
													   ,ART_REF
													   ,ART_CBAR
													   ,ART_TYPE
													   ,ART_CATEG
													   ,ART_LIB
													   ,ART_LIBC
													   ,ART_QTEDFT
													   ,ART_POIDSB
													   ,ART_POIDST
													   ,ART_POIDSN
													   ,ART_UB_ACH
													   ,ART_CD_ACH
													   ,ART_UC_ACH
													   ,ART_UB_STK
													   ,ART_CD_STK
													   ,ART_UC_STK
													   ,ART_UB_VTE
													   ,ART_CD_VTE
													   ,ART_UC_VTE
													   ,ART_R_UAUV
													   ,ART_R_USUV
													   ,ART_LONG
													   ,ART_LARG
													   ,ART_STOCK
													   ,ART_DORT
													   ,ART_P_ACH
													   ,ART_M_PRV
													   ,ART_I_PRV
													   ,ART_D_PRV
													   ,ART_S_PRV
													   ,ART_P_PRV
													   ,ART_P_COEF
													   ,ART_P_VTEB
													   ,ART_P_VTE
													   ,ART_P_EURO
													   ,ART_DTCREE
													   ,ART_DTMAJ
													   ,ART_USRMAJ
													   ,ART_NUMMAJ
													   ,XXX_IDCATE
													   ,XXX_IDPRES
													   ,XXX_IDDECL
													   ,XXX_DECLIN
													   ,XXX_ORIGIN
													   ) VALUES ('
														. '\'' . preg_replace('/\'/','\'\'',$CodeArticle) . '\',' //ART_CODE
														. '\'' . preg_replace('/\'/','\'\'',$CodeArticle) . '\',' //ART_REF
														. '\'' . preg_replace('/\'/','\'\'',$CodeArticle) . '\',' //ART_CBAR
														. '\'P\',' //ART_TYPE
														. '\'F\',' //ART_CATEG
														. '\'' . preg_replace('/\'/','\'\'', $productArray['name']) . ' ' . preg_replace('/\'/','\'\'', $declension) . '\',' //ART_LIB
														. '\'' . preg_replace('/\'/','\'\'', $reference) . '\',' //ART_LIBC
														. $minimalQuantit . ',' //ART_QTEDFT
														. $weight . ',' //ART_POIDSB
														. '0,' //ART_POIDST
														. $weight . ',' //ART_POIDSN
														. '\'U\',' // ART_UB_ACH
														. '1,' //ART_CD_ACH
													    . '\'U\',' //ART_UC_ACH
													    . '\'U\',' //ART_UB_STK
													    . '1,' //ART_CD_STK
													    . '\'U\',' //ART_UC_STK
													    . '\'U\',' //ART_UB_VTE
													    . '1,' //ART_CD_VTE
													    . '\'U\',' //ART_UC_VTE
													    . '1,' //ART_R_UAUV
													    . '1,' //ART_R_USUV
														. $width . ',' //ART_LONG
														. $height . ',' //ART_LARG
														. '\'M\',' //ART_STOCK
														. '0,' //ART_DORT
														. '0,' //ART_P_ACH
														. '\'M\',' //ART_M_PRV
														. '\'P\',' //ART_I_PRV
														. '\'S\',' //ART_D_PRV
														. '\'A\',' //ART_S_PRV
														. '0,' //ART_P_PRV
														. '0,' //ART_P_COEF
														. '0,' //ART_P_VTEB
														. '0,' //ART_P_VTE
														. '0,' //ART_P_EURO
														. '\''. Utility::getNoZeroDate($dateUpd) .'\',' //ART_DTCREE
														. '\''. Utility::getNoZeroDate($dateUpd) .'\',' //ART_DTMAJ
														. '\'WEB\',' //ART_USRMAJ
														. '1,' //ART_NUMMAJ
														. '\'' . $idCategoryDefault . '\',' //XXX_IDCATE
														. '\'' . $idProduct . '\',' //XXX_IDPRES
														. $IdDeclinaison .',' //XXX_IDDECL
														. '\'' . preg_replace('/\'/','\'\'', $declension) . '\',' //XXX_DECLIN
														. (int)$this->origin .')'
														/*
														
														. $idSupplier . ','
														. $idManufacturer . ','
														. $idShopDefault . ','
														. $idTaxRulesGroup . ','
														. (int) $onSale . ','
														. (int) $onlineOnly . ','
														. $ean13 . ','
														. $upc . ','
														. $ecotax . ','
														. $quantity . ','
														. $price . ','
														. $wholesalePrice . ','
														. $unity . ','
														. $unitPriceRatio . ','
														. $additionalShippingCost . ','
														. $reference . ','
														. $supplierReference . ','
														. $location . ','
														. $depth . ','
														. $outOfStock . ','
														. (int) $quantityDiscount . ','
														. (int) $customizable . ','
														. (int) $uploadableFiles . ','
														. (int) $textFields . ','
														. (int) $active . ','
														. (int) $availableForOrder . ','
														. '\'' . $availableDate . '\','
														. $condition . ','
														. $showPrice . ','
														. $indexed . ','
														. $visibility . ','
														. (int) $cacheIsPack . ','
														. (int) $cacheHasAttachments . ','
														. (int) $isVirtual . ','
														. $cacheDefaultAttribute . ','
														. '\'' . $dateAdd . '\','
														. '\'' . $dateUpd . '\','
														. (int) $advancedStockManagement . ','
														 */
					) or die ("<p>" . odbc_errormsg() . "</p>");
				}
			}
			echo $idProduct . '<br/>';
		
		}
	}

	public function synchronizeAll(){
		$this->synchronizePrestashopToGestimum();
	}
}