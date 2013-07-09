<?php 

class CSTS{
	const SHOP_ADDRESS = 'YOUR SHOP ADDRESS';
	const WEBSERVICE_KEY = 'YOUR WEBSERVICE KEY';
	const SQLSERVER_DB_CONNECTIONSTR = "YOUR SQL SERVER DATABASE CONNECTION STRING";
	const DB_USERNAME = "YOUR DATABASE USERNAME";
	const DB_PASSWORD = "YOUR DATABASE PASSWORD";

	public static function getShopAddress(){
		return self::SHOP_ADDRESS;
	}

	public static function getWebServiceKey(){
		return self::WEBSERVICE_KEY;
	}

	public static function getSQLServerConnectionString(){
		return self::SQLSERVER_DB_CONNECTIONSTR;
	}

	public static function getDataBaseUsername(){
		return self::DB_USERNAME;
	}

	public static function getDataBasePassword(){
		return self::DB_PASSWORD;
	}
}