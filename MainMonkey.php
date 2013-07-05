<?php 
require_once("./tools/AdvancedManipulationEngine.php");
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
$myEngine->retrieveData('addresses','2', NULL);
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