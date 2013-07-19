<?php 
include('./tools/bisAutoloader.php');
// Replace path and key with your own.
$myEngine = new AdvancedManipulationEngine(Constants::getShopAddress(), Constants::getWebServiceKey());
/*
$myEngine->createData(
						array(
							  'lastname' => 'tamerlank',
						  	  'firstname' => 'titi',
							  'email' => 'mayus@name.com',
							  'passwd' => 'mayus',
							  'note' => 'Homme à Lynda'
							  ),
						'customers'
					 );

*/
// The ID is important and is not updatable, provide it so that the corresponding entity will be updated.
/*$myEngine->updateData(
						array(
							  'id' => '5',
							  'lastname' => 'Vlodvek'
							  ),
						'customers'
					 );*/
//$myEngine->deleteData('7','customers');

//$c = new PDO(Constants::getSQLServerConnectionString(), Constants::getDataBaseUsername(), Constants::getDataBasePassword());
$c = odbc_connect('SQLSERVER2012', 'mayas-had', 'Ma11asH');
$query = odbc_exec($c, 'SELECT * FROM dbo.TIERS WHERE left(PCF_CODE,1) = \'w\'');
var_dump(odbc_result_all($query));
//$myCustomersMonkey = new CustomersMonkey($c, $myEngine, 100, 700, 'site A');

$myCustomersMonkey->synchronizeAll();
