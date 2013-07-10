<?php
require_once("AdvancedManipulationEngine.php");

class CustomersMonkey{
	
	protected $myAdvancedManipulationEngine;

	function __construct($advancedManipulationEngine){
	
		$this->myAdvancedManipulationEngine = $advancedManipulationEngine;
	}

	public function getCustomerAddress($customerId){
		
			$addresses = $this->myAdvancedManipulationEngine->retrieveData('addresses', 
				NULL, array('id_customer' => $customerId));
			$CustomerAddresses;
			$attribute = $addresses->children()->children()->children();
			foreach ($attribute as $key => $value) {
					switch ($key) {
						case 'address1':
							$CustomerAddresses['address1'] = $value;
							break;
						case 'address2':
							$CustomerAddresses['address2'] = $value;
							break;
						default:
							break;
					}
				}
			return $CustomerAddresses; // Returns an array containing the customers two first addresses
		
	}

	public function hasAConfirmedOrder($customerId){
		$orders = $this->myAdvancedManipulationEngine->retrieveData('orders',
			NULL,
			array('id_customer'	=>
			 $customerId));
		
		return ($orders->children()->children()->count() > 0); // True if list of orders is not null
	}

	public function synchronizeAll($sqlServerConnection, $origin, $from, $to){

		$xml = $this->myAdvancedManipulationEngine->retrieveData('customers', NULL, array('id'	=> '[' . $from . ',' . $to . ']'));
		
		$customers = $xml;
		$customer = $customers->children()->children();

		foreach ($customer as $keyCus => $valueCus){
			// Only one customer returned
			$idCustomer = 'NULL';
			$idShopGroup = 'NULL';
   			$idShop = 'NULL';
			$idGender = 'NULL';
			$idDefaultGroup = 'NULL';
			$idRisk = 'NULL';
  			$company = 'NULL';
    		$siret = 'NULL';
    		$ape = 'NULL';
    		$firstname = 'NULL';
    		$lastname = 'NULL';
    		$email = 'NULL';
			$passwd = 'NULL';
			$lastPasswdGen = 'NULL';
			$birthday = 'NULL';
			$newsletter = 'NULL';
			$ipRegistrationNewsletter = 'NULL';
			$newsletterDateAdd = 'NULL';
			$optin = 'NULL';
			$website = 'NULL';
			$outstandingAllowAmount = 'NULL';
			$showPublicPrices = 'NULL';
			$maxPaymentDays = 'NULL';
			$secureKey = 'NULL';
			$note = 'NULL';
			$active = 'NULL';
			$isGuest = 'NULL';
			$deleted = 'NULL';
			$dateAdd = 'NULL';
			$dateUpd = 'NULL';
			$address1 = 'NULL';
			$address2 = 'NULL';
			$phone = 'NULL';
			$phoneMobile = 'NULL';

			foreach ($valueCus as $key => $value) {
				
				switch ($key) {
					case 'id':
						$idCustomer = $value;
						break;
					case 'id_shop_group':
						$idShopGroup = $value;
						break;
					case 'id_shop':
						$idShop = $value;
						break;			
					case 'id_gender':
						$idGender = $value;
						break;
					case 'company':
						if ($value) $company = $value;
						break;
					case 'siret':
						if ($value) $siret = $value;
						break;
					case 'ape':
						if ($value) $ape = $value;
						break;
					case 'firstname':
						$firstname = $value;
						break;
					case 'lastname':
						$lastname = $value;
						break;
					case 'email':
						$email = $value;
						break;
					case 'passwd':
						$passwd = $value;
						break;
					case 'last_passwd_gen':
						$lastPasswdGen = $value;
						break;
					case 'birthday':
						if ($value) $birthday = $value;
						break;
					case 'newsletter':
						$newsletter = $value;
						break;
					case 'ip_registration_newsletter':
						if ($value) $ipRegistrationNewsletter = $value;
						break;
					case 'newsletter_date_add':
						if ($value) $newsletterDateAdd = $value;
						break;
					case 'optin':
						$optin = $value;
						break;
					case 'website':
						if ($value) $website = $value;
						break;
					case 'outstanding_allow_amount':
						$outstandingAllowAmount = $value;
						break;
					case 'show_public_prices':
						$showPublicPrices = $value;
						break;
					case 'max_payment_days':
						$maxPaymentDays = $value;
						break;
					case 'max_payment_days':
						$maxPaymentDays = $value;
						break;
					case 'secure_key':
						$secureKey = $value;
						break;
					case 'note':
						if ($value) $note = $value;
						break;
					case 'active':
						$active = $value;
						break;
					case 'is_guest':
						$isGuest = $value;
						break;
					case 'deleted':
						$deleted = $value;
						break;
					case 'date_add':
						$dateAdd = $value;
						break;					
					case 'date_upd':
						$dateUpd = $value;
						break;					
					default:
						break;					
				}
 				
			}
			if($this->hasAConfirmedOrder($idCustomer)){
				$customerAddress = $this->getCustomerAddress($idCustomer);
				
					$address1 = $customerAddress['address1'] ;

					$address2 = $customerAddress['address2'] ;

				$statement = $sqlServerConnection->prepare('PrestaClient '
								. $idCustomer . ','
								. $idShopGroup . ','
   								. $idShop . ','
								. $idGender . ','
								. $idDefaultGroup . ','
 								. $idRisk . ','
								. '\'' . preg_replace('/\'/','\'\'',$company) .'\','
								. '\'' . preg_replace('/\'/','\'\'',$siret) .'\','
								. '\'' . preg_replace('/\'/','\'\'',$ape) .'\','
								. '\'' . preg_replace('/\'/','\'\'',$firstname) .'\','
								. '\'' . preg_replace('/\'/','\'\'',$lastname) .'\','
								. '\'' . preg_replace('/\'/','\'\'',$email) . '\','
								. '\'' . preg_replace('/\'/','\'\'',$passwd) . '\','
								. '\'' . preg_replace('/\'/','\'\'',$lastPasswdGen) . '\','
								. '\'' . $birthday . '\','
								. preg_replace('/\'/','\'\'',$newsletter) . ','
								. '\'' . $ipRegistrationNewsletter . '\','
								. '\'' . $newsletterDateAdd .'\','
								. $optin . ','
								. '\'' . $website . '\','
								. '\'' . $outstandingAllowAmount . '\','
								. $showPublicPrices . ','
							    . $maxPaymentDays .','
								. '\'' . $secureKey . '\','
								. '\'' . $note . '\','
								. $active . ','
								. $isGuest . ','
								. $deleted . ','
								. '\'' . $dateAdd .'\','
								. '\'' . $dateUpd . '\','
								. '\'' . preg_replace('/\'/','\'\'',$address1) . '\','
								. '\'' . preg_replace('/\'/','\'\'',$address2) . '\','
								. '\'' . $phone . '\','
   								. '\'' . $phoneMobile . '\','
								. '\'' . $origin .  '\'');
				if(!$statement->execute()){
					$statement->debugDumpParams();
					print_r($statement->errorInfo());
				}else{
					echo 'Succès, client remonté : ' . $firstname . ' | ' . $lastname . ' | ' . $address1 . ' | ' . $address2 . '<br/>';
				}
			}
		}
	}
}