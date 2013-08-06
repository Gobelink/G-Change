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
			array('id_customer, address1', 'address2', 'postcode','city'), 
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
						case 'postcode':
							$addressesArray['postcode'] = $value;
							break;
						case 'city':
							$addressesArray['city'] = $value;
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
			$postcode = 'NULL';
			$city = 'NULL';
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
				
				$postcode = $customerAddresses['postcode'] ;
				
				$city = $customerAddresses['city'] ;
				
				$PcfCode = 'W'.$idCustomer;
				$CptNumero = '411'.$PcfCode;
				$Rs2 = '';
				
				if ($company='NULL'){
					$company= $firstname.' '. $lastname;
				}else{
					$Rs2= $firstname .' '.$lastname;
				} 

				$verif = odbc_exec($this->sqlServerConnection, CustomersConstants::getSelectPCFCODEString($PcfCode));
				
				$countArray = odbc_fetch_array($verif);
				
				foreach ($countArray as $key => $value) {
					$exists = $value;
				}
				$exists = $exists > 0;
				
				if ($exists){

				odbc_exec($this->sqlServerConnection,'UPDATE TIERS SET '
													  . ' [PCF_RS] = UPPER(\'' . $company . '\')'
												      . ' ,[PCF_RS2] = UPPER(\'' . $Rs2 . '\')'
													  . ' ,[PCF_RUE] = \'' . preg_replace('/\'/','\'\'',$address1) . '\''
													  . ' ,[PCF_COMP] = \'' . preg_replace('/\'/','\'\'',$address2) . '\''
													  . ' ,[PCF_CP] = \'' . preg_replace('/\'/','\'\'',$postcode) . '\''
													  . ' ,[PCF_VILLE] = \'' . preg_replace('/\'/','\'\'',$city) . '\''
												      . ' ,[PCF_EMAIL] = \'' . preg_replace('/\'/','\'\'',$email) . '\''
												      . ' ,[PCF_SIRET] = \'' . $siret . '\''
												      . ' ,[PCF_APE] = \'' . $ape . '\''
													  . ' ,[PCF_TEL1] = \'' . $phone . '\''
													  . ' ,[PCF_TEL2] = \'' . $phoneMobile . '\''
												      . ' ,[PCF_NUMMAJ] = [PCF_NUMMAJ]+1 ' 
													  . ' ,[XXX_MPAYDA] = ' . $maxPaymentDays
													  . ' ,[XXX_IDGEND] = ' . $idGender 
													  . ' ,[XXX_PROVEN] = \'' . $this->origin . '\''
													  . 'WHERE [PCF_CODE] = \'' . $PcfCode . '\''
													  
  			                                          . 'UPDATE CONTACTS SET '
													  . ' [CCT_PRENOM] = \'' . preg_replace('/\'/','\'\'',$firstname) . '\''
												      . ' ,[CCT_NOM] = \'' . preg_replace('/\'/','\'\'',$lastname) . '\''
													  . ' ,[CCT_EMAIL] = \'' . preg_replace('/\'/','\'\'',$email) . '\''
													  . 'WHERE [CCT_ORIGIN] = \'' . $PcfCode . '\''
													  
													  . 'UPDATE ADRESSES SET '
													  . ' [ADR_RS] = UPPER(\'' . $company . '\')'
												      . ' ,[ADR_RS2] = UPPER(\'' . $Rs2 . '\')'
													  . ' ,[ADR_RUE] = \'' . preg_replace('/\'/','\'\'',$address1) . '\''
													  . ' ,[ADR_COMP] = \'' . preg_replace('/\'/','\'\'',$address2) . '\''
													  . ' ,[ADR_CP] = \'' . preg_replace('/\'/','\'\'',$postcode) . '\''
													  . ' ,[ADR_VILLE] = \'' . preg_replace('/\'/','\'\'',$city) . '\''
													  . ' ,[ADR_TEL1] = \'' . preg_replace('/\'/','\'\'',$phone) . '\''
													  . ' ,[ADR_TEL2] = \'' . preg_replace('/\'/','\'\'',$phoneMobile) . '\''
													  . 'WHERE [ADR_CODE] = \'' . $PcfCode . '\'') or die ("<p>" . odbc_errormsg() . "</p>");
				} else {
				odbc_exec($this->sqlServerConnection,'INSERT INTO dbo.TIERS (
																	[PCF_CODE]
																   ,[PCF_TYPE]
																   ,[CPT_NUMERO]
 													               ,[PCF_RS]
																   ,[PCF_RS2]
																   ,[PCF_RUE]
																   ,[PCF_COMP]
																   ,[PCF_CP]
																   ,[PCF_VILLE]
																   ,[PCF_TEL1]
																   ,[PCF_TEL2]
																   ,[PCF_EMAIL]
																   ,[PCF_SIRET]
																   ,[PCF_APE]
																   ,[DEV_CODE]
																   ,[NAT_CODE]
																   ,[TAR_CODE]
																   ,[PCF_DORT]
																   ,[PCF_DTCREE]
																   ,[PCF_DTMAJ]
																   ,[PCF_USRMAJ]
																   ,[PCF_NUMMAJ]
																   ,[PCF_BLOQUE]
																   ,[PCF_USRCRE]
																   ,[XXX_CLTWEB]
																   ,[XXX_MPAYDA]
																   ,[XXX_IDGEND]
																   ,[XXX_PROVEN]															   
																   ) VALUES ('
								. '\'' . $PcfCode. '\','
								. '\'C \','
								. '\'' . $CptNumero . '\','
								. 'UPPER(\'' . $company . '\'),' //PCF_RS
								. 'UPPER(\'' . $Rs2 . '\'),' //PCF_RS2
								. '\'' . preg_replace('/\'/','\'\'',$address1) . '\','//PCF_RUE
								. '\'' . preg_replace('/\'/','\'\'',$address2) . '\','//PCF_COMP
								. '\'' . preg_replace('/\'/','\'\'',$postcode) . '\',' //PCF_CP
								. '\'' . preg_replace('/\'/','\'\'',$city) . '\',' //PCF_VILLE
								. '\'' . preg_replace('/\'/','\'\'',$phone) . '\','//PCF_TEL1
								. '\'' . preg_replace('/\'/','\'\'',$phoneMobile) . '\','//PCF_TEL2
								. '\'' . preg_replace('/\'/','\'\'',$email) . '\','//PCF_EMAIL
								. '\'' . preg_replace('/\'/','\'\'',$siret) . '\','//PCF_SIRET
								. '\'' . preg_replace('/\'/','\'\'',$ape) . '\','//PCF_APE
								. '\'EUR\' ,'//DEV_CODE
								. '\'001\' ,'//NAT_CODE
								. '\'WEB\' ,'//TAR_CODE
								. '0 ,'//PCF_DORT
								. '\'' . Utility::getNoZeroDate($dateAdd) .'\','//PCF_DTCREE
								. '\'' . Utility::getNoZeroDate($dateUpd) .'\','//PCF_DTMAJ
								. '\'WEB\' ,'//PCF_USRMAJ
								. '1 ,'//PCF_NUMMAJ
								. '0 ,'//PCF_BLOQUE
								. '\'WEB\' ,'//PCF_USRCRE
								. '1 ,'//XXX_CLTWEB
								. $maxPaymentDays . ','//XXX_MPAYDA
								. $idGender . ','//XXX_IDGEND
								. '\'' . $this->origin .'\')')//XXX_PROVEN
								/*. $idShopGroup . ','
   								. $idShop . ','
								. $idDefaultGroup . ','
 								. $idRisk . ','
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
								. '\'' . $secureKey . '\','
								. '\'' . $note . '\','
								. $active . ','
								. $isGuest . ','
								. $deleted . ')'*/
								or die ("<p>" . odbc_errormsg() . "</p>");
				odbc_exec($this->sqlServerConnection,'INSERT INTO CONTACTS (
																	CCT_NUMERO,
																	CCT_CODE,
																	CCT_ORIGIN,
																	CCT_TABLE,
																	CCT_CIVILE,
																	CCT_PRENOM,
																	CCT_NOM,
																	CCT_EMAIL
																	) VALUES ('
								. '\'' . $PcfCode. '\',' //CCT_NUMERO
								. '\'' . $PcfCode. '\',' //CCT_CODE
								. '\'' . $PcfCode. '\',' //CCT_ORIGIN
								. '\'PCF\' ,' //CCT_TABLE
								. '\'\' ,' //CCT_CIVILE
								. '\'' . preg_replace('/\'/','\'\'',$firstname) . '\','//CCT_PRENOM
								. '\'' . preg_replace('/\'/','\'\'',$lastname) . '\','//CCT_NOM
								. '\'' . preg_replace('/\'/','\'\'',$email) . '\')')//CCT_EMAIL
								or die ("<p>" . odbc_errormsg() . "</p>");
								
				odbc_exec($this->sqlServerConnection,'INSERT INTO ADRESSES (
															  ADR_TBL,
															  ADR_CODE,
															  ADR_NUMERO,
															  ADR_RS,
															  ADR_RS2,
															  ADR_RUE,
															  ADR_COMP,
															  ADR_CP,
															  ADR_VILLE,
															  ADR_TEL1,
															  ADR_TEL2
															 ) VALUES ('
								. '\'PCF\' ,' //ADR_TBL
								. '\'' . $PcfCode. '\',' //ADR_CODE
								. '\'001\' ,' //ADR_NUMERO
								. 'UPPER(\'' . $company . '\'),' //ADR_RS
								. 'UPPER(\'' . $Rs2 . '\'),' //ADR_RS2
								. '\'' . preg_replace('/\'/','\'\'',$address1) . '\',' //ADR_RUE
								. '\'' . preg_replace('/\'/','\'\'',$address2) . '\',' //ADR_COMP
								. '\'' . preg_replace('/\'/','\'\'',$postcode) . '\',' //ADR_CP
								. '\'' . preg_replace('/\'/','\'\'',$city) . '\',' //ADR_VILLE
								. '\'' . preg_replace('/\'/','\'\'',$phone) . '\',' //ADR_TEL1
								. '\'' . preg_replace('/\'/','\'\'',$phoneMobile) . '\')') //ADR_TEL2
								or die ("<p>" . odbc_errormsg() . "</p>");
					}
				}
			}
		}
}
