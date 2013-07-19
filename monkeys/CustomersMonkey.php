<?php

class CustomersMonkey implements monkey{
	
	protected $myAdvancedManipulationEngine;

	protected $from;
	protected $to;

	protected $sqlServerConnection;

	protected $origin;

	function __construct($sqlServerConnection, $advancedManipulationEngine, $from, $to, $origin){
	
		$this->myAdvancedManipulationEngine = $advancedManipulationEngine;

		$this->from = $from;
		$this->to = $to;

		$this->sqlServerConnection = $sqlServerConnection;

		$this->origin = $origin; // To see from where does the client come from
	}

	public function getCustomerAddress(){
		
		$address = $this->myAdvancedManipulationEngine->retrieveData(
			'addresses', 
			NULL,
			array('id_customer, address1', 'address2'), 
			array('id_customer' => '[' . $this->from . ',' . $this->to . ']')
			);

		$customersAddressesHashmap;
		$customersAddressesHashmapKey;
		$addressesArray;
		
		foreach ($address as $key => $values) {
			foreach ($values as $key => $value) {
					switch ($key) {
						case  'id_customer':
						$customersAddressesHashmapKey = $value;
						case 'address1':
							$addressesArray['address1'] = $value;
							break;
						case 'address2':
							$addressesArray['address2'] = $value;
							break;
						default:
							break;
				}
			}
			$customersAddressesHashmap[(string)$customersAddressesHashmapKey] = $addressesArray;
			$addressesArray = array();
		}
		return $customersAddressesHashmap;
	}

	public function customersConfirmedOrders(){
		$order = $this->myAdvancedManipulationEngine->retrieveData(
			'orders',
			NULL,
			array('id_customer'),
			array('id_customer' => '[' . $this->from . ',' . $this->to . ']')
			);

		$customersHavingClosedOrdersArray = array();

		foreach ($order as $key => $singleOrderAttributes) {
			foreach ($singleOrderAttributes as $key => $value) {
				if($key == 'id_customer') {
					$customersHavingClosedOrdersArray[] = $value;
				}
			}
		}
		return $customersHavingClosedOrdersArray;
	}

	public function hasAConfirmedOrder($idCustomer, $customersHavingClosedOrdersArray){

		return (in_array($idCustomer, $customersHavingClosedOrdersArray)); // True if the ID of this customer is contained in the table
	}

	public function synchronizeAll(){

		$customer = $this->myAdvancedManipulationEngine->retrieveData(
			'customers',
			NULL,
			NULL,
			array('id'	=> '[' . $this->from . ',' . $this->to . ']')
			);
		
		$customersAdresses = $this->getCustomerAddress();
		$customersHavingClosedOrdersArray = $this->customersConfirmedOrders();

		foreach ($customer as $keyCus => $valueCus){
			// Browsing every single customer fetched from the PrestaShop boutique.
			$idCustomer = NULL;
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

			if($this->hasAConfirmedOrder((int) $idCustomer, $customersHavingClosedOrdersArray)){

				$customerAddresses = $customersAdresses[(int)$idCustomer];
				
				$address1 = $customerAddresses['address1'] ;

				$address2 = $customerAddresses['address2'] ;

				$statement = $this->sqlServerConnection->prepare('PrestaClient '
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
								. '\'' . $this->origin .  '\'');
				if(!$statement->execute()){
					$statement->debugDumpParams();
					print_r($statement->errorInfo());
				}else{
					echo $idCustomer . '<br/>';
				}
			}
		}
	}
}
