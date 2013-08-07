<?php
class ProductsConstants{

	public static function getSelectProductsForCreationStoredProcedureCallString($siteOrigin){
		return ' EXEC dbo.Presta_Synchro_Get_Articles_For_Creation ' . $siteOrigin;
	}

	public static function getSelectProductsForUpdateStoredProcedureCallString($siteOrigin){
		return ' EXEC dbo.Presta_Synchro_Get_Articles_For_Creation ' . $siteOrigin;
	}

	public static function getSelectARTCODEString($productCode){
		return 
			'SELECT COUNT(*) FROM ARTICLES A WHERE A.ART_CODE = \''. $productCode .'\'';
	}

	public static function getProductUpdatingString(
 		$productName,
 		$declension,
 		$reference,
 		$minimalQuantit,
 		$weight,
 		$width,
 		$height,
 		$dateUpd,
 		$IdDeclinaison,
 		$CodeArticle){
 		
 		return 'UPDATE ARTICLES SET '
		. ' [ART_LIB] = \'' . preg_replace('/\'/','\'\'', $productName) . ' ' . preg_replace('/\'/','\'\'', $declension) . '\''
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
		. ',[XXX_IDDECL] =' . $IdDeclinaison
		. 'WHERE [ART_CODE] = \'' . $CodeArticle . '\'';
	}

	public static function getProductInsertingString(
		$CodeArticle,
		$productName,
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
		$origin
		){

		return 'INSERT INTO dbo.ARTICLES
		(ART_CODE
		,ART_REF
		,ART_CBAR
		,ART_TYPE
		,ART_CATEG
		,ART_TGAMME
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
		. '\'\',' // ART_TGAMME
		. '\'' . preg_replace('/\'/','\'\'', $productName) . ' ' . preg_replace('/\'/','\'\'', $declension) . '\',' //ART_LIB
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
		. (int)$origin .')'
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
		 */;
					
	}

}