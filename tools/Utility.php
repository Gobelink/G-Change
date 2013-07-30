<?php 
class Utility{
	function __construct(){

	}

	public static function getNoZeroDate($dateString){
		// This method makes sure that no 0-date is given from Prestashop, because SQLServer does not allow it
		// -0001-11-30 00:00:00 is returned by DateTime::format('0000-00-00 00:00:00')
		if($dateString == '0000-00-00 00:00:00'
			|| $dateString == '-0001-11-30 00:00:00' ){
			return NULL; // This date is the Gestimum default "no date" date
		}
		return $dateString;
	}
}