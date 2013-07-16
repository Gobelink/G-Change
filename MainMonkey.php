<?php 
include('./tools/autoloader.php');
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

$c = new PDO(Constants::getSQLServerConnectionString(), Constants::getDataBaseUsername(), Constants::getDataBasePassword());

$myOrdersMonkey = new OrdersMonkey($myEngine, 100, 105);

$myOrdersMonkey->synchronizeAll();