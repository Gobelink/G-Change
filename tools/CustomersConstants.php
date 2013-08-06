<?php
class CustomersConstants{
 public static function getSelectPCFCODEString($PcfCode){
 	return 
 	'SELECT COUNT(*) FROM TIERS T WHERE T.PCF_CODE = \''. $PcfCode .'\'';
 }
}