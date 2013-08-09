<?php 
class Utility{
	function __construct(){

	}

	public static function getProductPrice(
		$prixGrille,
		$prixPromo,
		$prixArticle,
		$origin	
		){

		if($origin == 2){ // We are synchronizing with presentation site
			return $prixGrille;
		}
		if ($prixPromo > 0) {
			return $prixPromo;
		}
		return $prixArticle;
	}

	public static function getProductLinkRewrite($productName){

		$stringWithoutSpecialChars = preg_replace('/[^a-z\h]/', '', strtolower($productName));
		
		$stringWithoutBlanks = preg_replace('/\h/',	'-', $stringWithoutSpecialChars);
		
		$stringWithoutMultipleDashes = preg_replace('/[-]+/',	'-', $stringWithoutBlanks);
		
		$correctRewrite = trim($stringWithoutMultipleDashes, '-');

		return $correctRewrite;
	}

	public static 
	public static function getNoZeroDate($dateString){
		// This method makes sure that no 0-date is given from Prestashop, because SQLServer does not allow it
		// -0001-11-30 00:00:00 is returned by DateTime::format('0000-00-00 00:00:00')
		if($dateString == '0000-00-00 00:00:00'
			|| $dateString == '-0001-11-30 00:00:00' ){
			return NULL; // This date is the Gestimum default "no date" date
		}
		return $dateString;
	}

	public static function getOrPrestashopQueryStringFromArray($theArray){
		
		$idsQueryString = '';

		foreach ($theArray as $key => $value) {
			$idsQueryString = $idsQueryString . '|' . $value;
		}
		return trim($idsQueryString, '|');
	}
}