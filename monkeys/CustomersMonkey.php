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

	public function getCustomersAddresses(){
		
		$address = $this->myAdvancedManipulationEngine->retrieveData(
			'addresses', 
			NULL,
			array('id','id_customer', 'address1', 'address2', 'postcode','city', 'phone', 'phone_mobile'), 
			array('id_customer' => '[' . $this->from . ',' . $this->to . ']')
			);


		$customersAddressesHashmap;
		$customersAddressesHashmapKey;

		$addressesArray = array();
		
		foreach ($address as $key => $values) {
			$addressArray = array();
			foreach ($values as $key => $value) {
				switch ($key) {
					case 'id':
						$id = $value;
						break;
					case 'id_customer':
						$customersAddressesHashmapKey = $value;
						break;
					default:
						$addressArray[$key] = $value;
						break;
				} 
				$addressesArray[(string)$id] = $addressArray;
			}
			$customersAddressesHashmap[(string)$customersAddressesHashmapKey] = $addressesArray;			
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
	public function getCustomersFromPrestashop(){
		return $this->myAdvancedManipulationEngine->retrieveData(
			'customers',
			NULL,
			NULL,
			array('id'	=> '[' . $this->from . ',' . $this->to . ']')
			);
	}

	public function synchronizePrestashopToGestimum(){

		$customer = $this->getCustomersFromPrestashop();

		$customersAdresses = $this->getCustomersAddresses();
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
			$postcode = 'NULL';
			$city = 'NULL';
			$phone = 'NULL';
			$phoneMobile = 'NULL';
			$id = NULL;

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
						if ($value) $company = Constants::upperString($value);
						break;
					case 'siret':
						if ($value) $siret = $value;
						break;
					case 'ape':
						if ($value) $ape = $value;
						break;
					case 'firstname':
						$firstname = Constants::upperString($value);
						break;
					case 'lastname':
						$lastname = Constants::upperString($value);
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

				$customerAddresses = $customersAdresses[(int) $idCustomer];

				$noAddress = 0;

				foreach($customerAddresses as $id => $address){

					$noAddress += 1;
			
					$address1 = Constants::upperString($customerAddresses[$id]['address1']) ;

					$address2 = Constants::upperString($customerAddresses[$id]['address2']) ;
					
					$postcode = $customerAddresses[$id]['postcode'] ;
					
					$city = Constants::upperString($customerAddresses[$id]['city']) ;
					
					$phoneMobile = $customerAddresses[$id]['phone_mobile'];

					$phone = $customerAddresses[$id]['phone'];

					$PcfCode = 'W'.$idCustomer;
					$CptNumero = '411'.$PcfCode;
					$Rs2 = '';
					
					if ($company='NULL'){
						$company= $firstname.' '. $lastname;
					}else{
						$Rs2= $firstname .' '.$lastname;
					} 

					if (Constants::existsInDB(
						CustomersConstants::getSelectPCFCODEString($PcfCode),
						$this->sqlServerConnection)){
						if (Constants::existsInDB(
							CustomersConstants::getSelectADRNUMEROString($PcfCode, str_pad($noAddress, 3, "0", STR_PAD_LEFT)),
							$this->sqlServerConnection)){
							odbc_exec(
								$this->sqlServerConnection,
								CustomersConstants::getAdressesUpdateString(
									$company,
							 		$Rs2,
							 		str_pad($noAddress, 3, "0", STR_PAD_LEFT),
							 		$address1,
							 		$address2,
							 		$postcode,
							 		$city,
							 		$email,
							 		$siret,
							 		$ape,
							 		$phone,
							 		$phoneMobile,
							 		$maxPaymentDays,
							 		$idGender,
							 		$this->origin,
							 		$PcfCode,
							 		$firstname,
							 		$lastname,
							 		$email)
							) or die ("<p>" . odbc_errormsg() . "</p>");
						}else{
						odbc_exec(
									$this->sqlServerConnection,
									CustomersConstants::getAdressesInsertString(
										$company,
								 		$Rs2,
								 		str_pad($noAddress, 3, "0", STR_PAD_LEFT),
								 		$address1,
								 		$address2,
								 		$postcode,
								 		$city,
								 		$email,
								  		$phone,
								 		$phoneMobile,
								 		$PcfCode)
								) or die ("<p>" . odbc_errormsg() . "</p>");
						}
						odbc_exec(
									$this->sqlServerConnection,
									CustomersConstants::getContactsUpdateString(
										$company,
								 		$Rs2,
								 		$address1,
								 		$address2,
								 		$postcode,
								 		$city,
								 		$email,
								 		$siret,
								 		$ape,
								 		$phone,
								 		$phoneMobile,
								 		$maxPaymentDays,
								 		$idGender,
								 		$this->origin,
								 		$PcfCode,
								 		$firstname,
								 		$lastname,
								 		$email,
								 		$CptNumero,
								 		$dateAdd,
								 		$dateUpd)
								 	) or die ("<p>" . odbc_errormsg() . "</p>");
					}else{
							odbc_exec(
								$this->sqlServerConnection,
								CustomersConstants::getCustomersContactsAdressesInsertString(
									$company,
							 		$Rs2,
							 		str_pad($noAddress, 3, "0", STR_PAD_LEFT),
							 		$address1,
							 		$address2,
							 		$postcode,
							 		$city,
							 		$email,
							 		$siret,
							 		$ape,
							 		$phone,
							 		$phoneMobile,
							 		$maxPaymentDays,
							 		$idGender,
							 		$this->origin,
							 		$PcfCode,
							 		$firstname,
							 		$lastname,
							 		$email,
							 		$CptNumero,
							 		$dateAdd,
							 		$dateUpd
							 	)
							) or die ("<p>" . odbc_errormsg() . "</p>");
						}					
				}
			}
		}
	}
	public function synchronizeAll(){
		$this->synchronizePrestashopToGestimum();
	}
}
