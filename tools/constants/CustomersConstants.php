<?php
class CustomersConstants{
	public static function getSelectPCFCODEString($PcfCode){
 		return 
 		'SELECT COUNT(*) FROM TIERS T WHERE T.PCF_CODE = \''. $PcfCode .'\'';
 	}

 	public static function getCustomersContactsAdressesUpdateString(
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
 		$origin,
 		$PcfCode,
 		$firstname,
 		$lastname,
 		$email
 		){
 		return 'UPDATE TIERS SET '
													  . ' [PCF_RS] = UPPER(\'' . $company . '\')'
												      . ' ,[PCF_RS2] = UPPER(\'' . $Rs2 . '\')'
													  . ' ,[PCF_RUE] = \'' . preg_replace('/\'/','\'\'',$address1) . '\''
													  . ' ,[PCF_COMP] = \'' . preg_replace('/\'/','\'\'',$address2) . '\''
													  . ' ,[PCF_CP] = \'' . preg_replace('/\'/','\'\'',$postcode) . '\''
													  . ' ,[PCF_VILLE] = \'' . preg_replace('/\'/','\'\'',$city) . '\''
												      . ' ,[PCF_EMAIL] = \'' . preg_replace('/\'/','\'\'',$email) . '\''
												      . ' ,[PCF_SIRET] = \'' . $siret . '\''
												      . ' ,[PCF_APE] = \'' . $ape . '\''
													  . ' ,[PCF_TEL1] = \'' . preg_replace('/\'/','\'\'',$phone) . '\''
													  . ' ,[PCF_TEL2] = \'' . preg_replace('/\'/','\'\'',$phoneMobile) . '\''
												      . ' ,[PCF_NUMMAJ] = [PCF_NUMMAJ]+1 ' 
													  . ' ,[XXX_MPAYDA] = ' . $maxPaymentDays
													  . ' ,[XXX_IDGEND] = ' . $idGender 
													  . ' ,[XXX_PROVEN] = \'' . $origin . '\''
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
													  . 'WHERE [ADR_CODE] = \'' . $PcfCode . '\'';
		}

	public function getCustomersContactsAdressesInsertString(
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
 		$origin,
 		$PcfCode,
 		$firstname,
 		$lastname,
 		$email,
 		$CptNumero,
 		$dateAdd,
 		$dateUpd
 		){
    	return	'INSERT INTO TIERS (
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
								. '\'' . $origin .'\')' . //XXX_PROVEN
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

								'INSERT INTO CONTACTS (
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
								. '\'' . preg_replace('/\'/','\'\'',$email) . '\')' . //CCT_EMAIL

								'INSERT INTO ADRESSES (
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
								. '\'' . preg_replace('/\'/','\'\'',$phoneMobile) . '\')'; //ADR_TEL2
	}

}