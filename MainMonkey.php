<?php 
require_once("./tools/AdvancedManipulationEngine.php");
require_once("./tools/CustomersMonkey.php");
require_once("./tools/OrdersMonkey.php");
require_once("./tools/ProductsMonkey.php");
include("./tools/Constants.php");
// Replace path and key with your own.
$myEngine = new AdvancedManipulationEngine(CSTS::getShopAddress(), CSTS::getWebServiceKey());
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

$c = new PDO(CSTS::getSQLServerConnectionString(), CSTS::getDataBaseUsername(), CSTS::getDataBasePassword());

$myProductsMonkey = new ProductsMonkey($c, $myEngine, 100, 150);

$myProductsMonkey->synchronizeAll();