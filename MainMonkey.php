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

// For the moment, displays customers with one table per customer.
/*$xml = $myEngine->retrieveData('addresses', NULL, NULL);
$resources = $xml->children()->children();
if(sizeof($resources) > 1){
	echo sizeof($resources);
	//$resources = $resources->children();
	foreach ($resources as $singleResource){
		$attributes = $singleResource->children();
		echo '<table border="5">';
				
		foreach ($singleResource as $key => $value){
				echo '<tr>';
				echo '<th>' . $key . '</th><td>' . $value .'</td>';
				echo '</tr>';
		}
		echo '</table>';
	}
}else{
	echo sizeof($resources);
	$resource = $resources->children();
	echo '<table border="5">';
	foreach ($resource as $key => $value){
		echo '<tr>';
		echo '<th>' . $key . '</th><td>' . $value .'</td>';
		echo '</tr>';
	}
	echo '</table>';
}*/

$c = new PDO(CSTS::getSQLServerConnectionString(), CSTS::getDataBaseUsername(), CSTS::getDataBasePassword());
/*

$myCMonkey = new CustomersMonkey($myEngine, 1130, 2000);
//$myCMonkey->synchronizeAll($c, 'Site A');
//$myCMonkey->getCustomerAddress();
//$customersHavingClosedOrdersArray = $myCMonkey->customersConfirmedOrders();
//var_dump($myCMonkey->hasAConfirmedOrder(555, $customersHavingClosedOrdersArray));
$myCMonkey->synchronizeAll($c, 'Site A');
*/


$myProductsMonkey = new ProductsMonkey($c, $myEngine, 100, 100);

$productOptionsValuesNames = $myProductsMonkey->getProductOptionsValuesNames();
echo ($productOptionsValuesNames[5696]);
/*
foreach ($myProductsMonkey->getProductsFromPrestashop() as $key => $product) {
	echo $key . '<br/>';
	foreach ($product as $productAttributeKey => $productAttributeValue) {
		if($productAttributeKey == 'product_option_values'){
			foreach ($productAttributeValue as $key => $optionName) {
				echo $optionName . '<br/>';
			}
		}else{
			//echo $productAttributeKey . ' : ' . $productAttributeValue . '<br/>';
		}
	}
	echo "_________________________________________________________________________________________________________________ <br/>";
}
//$myProductsMonkey->synchronizeAll();
*/
/*/function get_Datetime_Now() {
    $tz_object = new DateTimeZone('Brazil/East');
    //date_default_timezone_set('Brazil/East'); 
    $datetime = new DateTime();
    $datetime->setTimezone($tz_object);     
return $datetime->format('Y\-m\-d\ h:i:s');
}

$c = new PDO(CSTS::getSQLServerConnectionString(), CSTS::getDataBaseUsername(), CSTS::getDataBasePassword());

	$id_customer = 1258;
 	$id_gender 	 = 1;
  	$company     = '\'Gestimum\'';
    $siret  	 = '\'\'';
    $ape  		 = '\'\'';
    $firstname   = '\'Heinrich\'';
    $lastname    = '\'Vlodvek\'';
    $email  	 = '\'pass@example.toto\'';
	$newsletter_date_add = '\'' . get_Datetime_Now() . '\'';
	$max_payment_days = 10;
	$date_add = '\'' . get_Datetime_Now() . '\'';
	$date_upd = '\'' . get_Datetime_Now() . '\'';

$sth = $c->prepare('PrestaClient '. $id_customer .','
									. $id_gender .','
									. $company .','
									. $siret .','
									. $ape .','
									. $firstname .','
									. $lastname .','
									. $email .','
									. $newsletter_date_add .','
									. $max_payment_days .','
									. $date_add .','
									. $date_upd);
if($sth->execute()) {
		echo 'cc';} else {echo 'nn <br/>';}
		$sth->debugDumpParams();
$arr = $sth->errorInfo();
		print_r($arr);

		/*$code = '700';
		$sth = $c->prepare('SelectTiers' );
		//$sth->bindParam(':code', $code, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 15);
		$sth->execute();
		$sth->debugDumpParams(); echo '<br/>';
		$arr = $sth->errorInfo();
		print_r($arr);
		$resultat = $sth->fetchAll();
		//print "La procédure a retourné : $res\n";
		var_dump($resultat);
		foreach($resultat as $key => $value){
			echo $key . ' | ' . $value . '<br/>';
		}*/