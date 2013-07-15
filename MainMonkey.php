<?php 
require_once("./tools/AdvancedManipulationEngine.php");
require_once("./monkeys/interfaces/monkey.php");
require_once("./monkeys/CustomersMonkey.php");
require_once("./monkeys/OrdersMonkey.php");
require_once("./monkeys/ProductsMonkey.php");
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

$myOrdersMonkey = new OrdersMonkey($myEngine, 100, 105);

$myOrdersMonkey->synchronizeAll();