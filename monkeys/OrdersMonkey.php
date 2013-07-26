<?php

class OrdersMonkey implements monkey{
	
	protected $myAdvancedManipulationEngine;
	protected $sqlServerConnection;
	protected $from;
	protected $to;

	function __construct($sqlServerConnection, $advancedManipulationEngine, $from, $to){
		$this->myAdvancedManipulationEngine = $advancedManipulationEngine;
	
		$this->sqlServerConnection = $sqlServerConnection;
		$this->from = $from;
		$this->to = $to;
	}

	public function getOrPrestashopQueryStringFromArray($theArray){
		
		$idsQueryString = '';

		foreach ($theArray as $key => $value) {
			$idsQueryString = $idsQueryString . '|' . $value;
		}
		return trim($idsQueryString, '|');
	}

	public function getAddressesOfOrders($ordersAddressesIds){
		
		$addressesByIdsHashMap = array();
		$addressesByIdsArray;
		$addressesByIdsHashMapKey;

		$address = $this->myAdvancedManipulationEngine->retrieveData(
			'addresses', 
			NULL,
			array('id', 'address1', 'address2', 'postcode', 'city'), 
			array('id' => '[' . $this->getOrPrestashopQueryStringFromArray($ordersAddressesIds) . ']')
			);

		foreach ($address as $key => $values) {
			foreach ($values as $key => $value) {
				switch ($key) {
					case 'id':
						$addressesByIdsHashMapKey = $value;
					break;
					default:
						$addressesByIdsArray[(string) $key] = $value;
					break;
				}
			}
			$addressesByIdsHashMap[(string) $addressesByIdsHashMapKey] = $addressesByIdsArray;
		}
		return $addressesByIdsHashMap;
	}

	public function getProductsOfCarts($idCartsArray){

		$productsByCartIdHashMap = array();
		$productsByCartIdHashMapKey;

		$productsArray = array();
		$idproduct;
		$associationsArray;

		$cart = $this->myAdvancedManipulationEngine->retrieveData(
			'carts',
			NULL,
			NULL,
			array('id' => '[' . $this->getOrPrestashopQueryStringFromArray($idCartsArray) . ']')
			);

		foreach ($cart as $key => $singleCart) {
			foreach ($singleCart as $cartKey => $cartValue) {
				switch ($cartKey) {
					case 'id':
						$productsByCartIdHashMapKey = $cartValue;
						//echo $cartValue . '<br/>';
						break;
					case 'associations':
						foreach (AdvancedManipulationEngine::getGrandChildren($cartValue) as $key => $value) {
							foreach ($value->children() as $associationKey => $associationValue) {
								switch ($associationKey) {
									case 'id_product':
										$idProduct = $associationValue;
										break;				
									default:
										$associationsArray[(string) $associationKey] = $associationValue;
										break;
								}
							}
							$productsArray[(string) $idProduct] = $associationsArray;
							$associationsArray = array();
						}
							break;
						default:
							break;
					}
				$productsByCartIdHashMap[(string) $productsByCartIdHashMapKey] = $productsArray;
				$productsArray = array();
			}
		}
	}

	public function getOrderDataArrays($order){

		$ordersDeliveryAddressesIds = array(); // getting the orders delivery addresses arrays

		$ordersInvoiceAddressesIds = array(); // getting the orders invoice addresses arrays

		$ordersCartAddressesIds = array(); // getting the orders carts


		foreach ($order as $key => $singleOrderAttributes) {
			foreach ($singleOrderAttributes as $key => $value) {
				switch ($key) {
					case 'id_address_delivery':
						$ordersDeliveryAddressesIds[] = $value;
						break;
					case 'id_address_invoice':
						$ordersInvoiceAddressesIds[] = $value;
						break;
					case 'id_cart':
						$ordersCartAddressesIds[] = $value;
						break;
					default:
						break;
				}
			}
		}
		$orderDataArrays['delivery_address'] = $ordersDeliveryAddressesIds;
		$orderDataArrays['invoice_address'] = $ordersInvoiceAddressesIds;
		$orderDataArrays['id_cart'] = $ordersCartAddressesIds;

		return $orderDataArrays;
	}

	public function getOrderRows($orderAssociationsAttribute){

		$orderRows = AdvancedManipulationEngine::getGrandChildren($orderAssociationsAttribute);
		$productArray;
		$orderRowsArray = array();

		foreach ($orderRows as $key => $singleOrderRow) {
			foreach ($singleOrderRow as $singleOrderRowKey => $singleOrderRowValue) {
				$productArray[$singleOrderRowKey] = $singleOrderRowValue;
			}	
			$orderRowsArray[] = $productArray;
			$productArray = array(); // Making sure that no product attribute ends up in an another product
		}
		return $orderRowsArray;
	}

	public function getOrders(){

		$order = $this->myAdvancedManipulationEngine->retrieveData(
			'orders',
			NULL,
			NULL,
			array('id' => '[' . $this->from . ',' . $this->to . ']')
			);

		$ordersHashmap = array();
		$ordersHashmapKey;
		$ordersArray;

		$orderDataArrays = $this->getOrderDataArrays($order);
		
		$deliveryAddressesOfOrders = $this->getAddressesOfOrders($orderDataArrays['delivery_address']);
		
		$invoiceAddressOfOrders = $this->getAddressesOfOrders($orderDataArrays['invoice_address']);

		$cartProductsArray = $this->getProductsOfCarts($orderDataArrays['id_cart']);

		foreach ($order as $key => $singleOrderAttributes) {
			foreach ($singleOrderAttributes as $key => $value) {
				switch($key){
					case 'id':
					$ordersHashmapKey = $value;
					break;
				case 'id_address_delivery':
					$ordersArray['delivery_address'] = $deliveryAddressesOfOrders[(string) $value];
					break;
				case 'id_address_invoice':
					$ordersArray['invoice_address'] = $invoiceAddressOfOrders[(string) $value];
				case 'id_cart':
					$ordersArray['order_products'] = $cartProductsArray[(string) $value];
					break;
				case 'associations':
					$ordersArray['orderRows'] = $this->getOrderRows($value);
					break;
				default:
					$ordersArray[(string)$key] = $value;
					break;
				}
			}
			$ordersHashmap[(string) $ordersHashmapKey] = $ordersArray;
			$ordersArray = array(); // reinitializing the array
		}
		///////////////////////////// PRINTING
		/*foreach ($ordersHashmap as $key => $value) {
			foreach ($value as $singleOrderkey => $singleOrdervalue) {
				switch ($singleOrderkey) {
					/*case 'delivery_address':
						echo "DELIVERY ADDRESS";
						foreach ($singleOrdervalue as $addressElementKey => $addressElementValue) {
							echo '|_'  . $addressElementKey . ' : ' . $addressElementValue . '<br/>';
						}
					case 'invoice_address':
						echo "INVOICE ADDRESS";
						foreach ($singleOrdervalue as $addressElementKey => $addressElementValue) {
							echo '|_'  . $addressElementKey . ' : ' . $addressElementValue . '<br/>';
						}
						break;
					case 'order_products':
						echo "ORDER PRODUCTS";
						foreach ($singleOrdervalue as $productKey => $productValue) {
							echo '|_' . $productKey . '<br/>';
							foreach ($productValue as $productAttributeKey => $productAttributeValue) {
								echo '|____' . $productAttributeKey . ' : ' . $productValue . '<br/>';
							}
						}
					case 'orderRows':
						echo "Rows <br/>";
						foreach ($singleOrdervalue as $rowKey => $rowValue) {
							foreach ($rowValue as $key => $attribute) {
								echo '______' . $key . ':::::::>' . $attribute  . '<br/>';
							}
						}
						break;
					default:
						//echo $singleOrderkey . ' : ' . $singleOrdervalue . '<br/>';
						break;
				}
			}
		}*/
		return $ordersHashmap;
	}

	public function synchronizeAll(){
		
		$myPSOrders = $this->getOrders();
		
		$DocType = 'V';
		$DocStype = 'C';
		$CodeArt = '\'WEBPRODUCT\'';

		 
		foreach ($myPSOrders as $orderID => $currentOrder){
			//$CodeClient = '\'W\'+'.$currentOrder['id_customer'];
		 	$CodeClient = '\'W'.$currentOrder['id_customer'].'\'';
		 	$Tva = $currentOrder['total_paid_tax_incl']- $currentOrder['total_paid_tax_excl'];
		 
		 	$ExecPsDocNumero = 'DECLARE @NUMERO INT
		 	EXEC G2GETNEWNUMERO \'N_DOC\', 1, @NUMERO OUTPUT		
		 	SELECT @NUMERO';
		 
		 	$ExecPsDocPiece = ' DECLARE @Identifiant VARCHAR(6)
		 	SET @Identifiant = \'N_VTEC\'
		 
  	     	DECLARE @Prefixe VARCHAR (4)
		 	SET @Prefixe = LEFT((SELECT REPLACE (SP.SOC_PRMTXT, \' \', \'\') FROM SOC_PARAMS SP WHERE SP.SOC_PARAM = @Identifiant),5)
		 
			DECLARE @Resultat VARCHAR(15)
			EXEC G2GetNewPiece @Identifiant, @Prefixe, @Resultat OUTPUT
			
			SELECT @Resultat';
			
			$IdDocGestimum = odbc_exec($this->sqlServerConnection,$ExecPsDocNumero) or die ("<p>" . odbc_errormsg() . "</p>"); 
			
			$DocNumero = substr (str_repeat("0",10).odbc_result($IdDocGestimum,1),-10);
			odbc_close($this->sqlServerConnection); 
			
			$connect = odbc_connect(Constants::getSQLServerConnectionString(),Constants::getDataBaseUsername(), Constants::getDataBasePassword());
			$IdDocPiece = odbc_exec($connect,$ExecPsDocPiece)or die ("<p>" . odbc_errormsg() . "</p>"); 
			$DocPiece = odbc_result($IdDocPiece,1);
			odbc_close($connect);

			$i = 0;
			foreach ($currentOrder['orderRows'] as $key => $currentProduct) {
				$i+= 16;
				$priceOfQuantity = $currentProduct['product_price'] * $currentProduct['product_quantity'];
				$queryForInsertingLines = ' INSERT INTO LIGNES (DOC_NUMERO, -- 1
									 LIG_NUMERO, -- 2
									 LIG_SUBNUM, -- 3
									 DEP_CODE, -- 4
									 LIG_NLOT, -- 5
									 LIG_TYPE, -- 6
									 ART_CODE, -- 7
									 ART_TGAMME, -- 8
									 ART_GAMME, -- 9
									 ART_REFFRS, -- 10 
									 ART_REFCLI, -- 11
									 LIG_LIB, -- 12 
									 LIG_QTE, -- 13 
									 LIG_P_BRUT, -- 14
									 LIG_REMISE, -- 15
									 LIG_P_NET, -- 16
									 LIG_TOTAL, -- 17
									 LIG_Q_CMDE, -- 18
									 LIG_Q_REL, -- 19 
									 LIG_Q_LIVR, -- 20
									 LIG_Q_FACT, -- 21
									 LIG_P_BASE, -- 22
									 LIG_FRENDU, -- 23
									 LIG_GP, -- 24
									 PRM_CODE, -- 25
									 LIG_FRAPP, -- 26
									 LIG_DOUANE, -- 27
									 LIG_COEF, -- 28
									 LIG_POIDSB, -- 29
									 LIG_POIDST, -- 30 
									 LIG_POIDSN, -- 31
									 LIG_POIDS, -- 32
									 ART_NCOLIS, -- 33
									 LIG_NCOLIS, -- 34
									 LIG_LONG, -- 35
									 LIG_LARG, -- 36
									 LIG_HAUT, -- 37
									 LIG_SURFAC, -- 38
									 LIG_VOLUTE, -- 39
									 LIG_VOLUME, -- 40
									 LIG_NUMLOT, -- 41
									 LIG_UC, -- 42
									 LIG_COND, -- 43
									 LIG_UB, -- 44
									 LIG_R_UCUV, -- 45
									 LIG_TSTOCK, -- 46
									 REP_CODE, -- 47
									 TAR_CODE, -- 48
									 LIG_PRIXAU, -- 49
									 LIG_P_PRV, -- 50
									 LIG_COUT, -- 51
									 LIG_FRAIS, -- 52
									 LIG_FRAIS2, -- 53
									 LIG_FRAIS3, -- 54
									 PRJ_CODE, -- 55
									 LIG_DT_CMD, -- 56
									 LIG_N_CMD, -- 57
									 LIG_DTCRE, -- 58
									 LIG_USRCRE, -- 59
									 LIG_DTMAJ, -- 60
									 LIG_USRMAJ, -- 61
									 LIG_NUMMAJ, -- 62
									 NAT_TVATX, -- 63
									 NAT_TVATYP -- 64
								 ) VALUES ('
				.'\''.$DocNumero.'\','//DOC_NUMERO -- 1
				.'RIGHT(REPLICATE(\'0\',5)+CAST('.$i.' AS VARCHAR(5)),5),' //LIG_NUMERO -- 2
				.'00000,' //LIG_SUBNUM -- 3
				.'001,' // DEP_CODE -- 4
				.'0,' //LIG_NLOT -- 5
				.'\'P\',' //LIG_TYPE -- 6
				.'\''.$currentProduct['product_attribute_id'] .'\',' // ART_CODE -- 7
				.'\'\',' //ART_TGAMME -- 8
				.'\'\',' //ART_GAMME -- 9
				.'\'\',' //ART_REFFRS -- 10
				.'\'\',' //ART_REFCLI -- 11
				.'\''.preg_replace('/\'/','\'\'',$currentProduct['product_name']) . '\',' //LIG_LIB -- 12
				.$currentProduct['product_quantity'] . ',' //LIG_QTE -- 13
				.$currentProduct['product_price'].',' //LIG_P_BRUT -- 14
				. '\'\',' //LIG_REMISE -- 15
				. $currentProduct['product_price'].',' //LIG_P_NET -- 16
				. $priceOfQuantity .',' //LIG_TOTAL -- 17
				.$currentProduct['product_quantity'].',' //LIG_Q_CMDE -- 18
				.'0,' //LIG_Q_REL -- 19
				.'0,' //LIG_Q_LIVR -- 20
				.'0,' //LIG_Q_FACT -- 21 
				. $currentProduct['product_price'].',' //LIG_P_BASE -- 22
			    .'0,' //LIG_FRENDU -- 23
				.'\'\',' //LIG_GP -- 24
				.'\'\',' //PRM_CODE -- 25
				.'0,' //LIG_FRAPP -- 26
				.'0,' //LIG_DOUANE -- 27
				.'0,' //LIG_COEF -- 28
				.'0,' //LIG_POIDSB -- 29
				.'0,' //LIG_POIDST -- 30
				.'0,' //LIG_POIDSN -- 31
				.'0,' //LIG_POIDS -- 32
				.'0,' //ART_NCOLIS -- 33
				.'0,' //LIG_NCOLIS -- 34
				.'0,' //LIG_LONG -- 35
				.'0,' //LIG_LARG -- 36
				.'0,' //LIG_HAUT -- 37
				.'0,' //LIG_SURFAC -- 38
				.'0,' //LIG_VOLUTE -- 39
				.'0,' //LIG_VOLUME -- 40
				.'\'\',' //LIG_NUMLOT -- 41
				.'\'U\',' //LIG_UC -- 42
				.'1,'//LIG_COND -- 43
				.'\'U\',' //LIG_UB -- 44
				.'1,' //LIG_R_UCUV -- 45
				.'\'M\',' //LIG_TSTOCK -- 46
				.'\'\',' //REP_CODE -- 47
				.'\'\',' //TAR_CODE -- 48
				.'1,' //LIG_PRIXAU -- 49
				.'0,' //LIG_P_PRV -- 50
				.'0,' //LIG_COUT -- 51
				.'0,' //LIG_FRAIS -- 52 
				.'0,' //LIG_FRAIS2 -- 53 
				.'0,' //LIG_FRAIS3 -- 54
				.'0,' //PRJ_CODE -- 55
				.'\'' . Utility::getNoZeroDate($currentOrder['invoice_date']) . '\','//LIG_DT_CMD -- 56
				.'\''.$DocPiece.'\',' //LIG_N_CMD -- 57
				.'GETDATE(),' //LIG_DTCRE
				.'\'WEB\',' //LIG_USRCRE
				.'GETDATE(),' //LIG_DTMAJ
				.'\'WEB\',' //LIG_USRMAJ
				.'1,' //LIG_NUMMAJ
				.'19.6,' //NAT_TVATX
				.'\'F\')'//NAT_TVATYP
				;
				odbc_exec($this->sqlServerConnection,$queryForInsertingLines) or die ("<p>" . odbc_errormsg() . "</p>");
				odbc_close($this->sqlServerConnection); 
			}
			odbc_exec($this->sqlServerConnection,' INSERT INTO dbo.DOCUMENTS (
													  DOC_TYPE,
													  DOC_STYPE,
													  DOC_RPIECE,
													  DOC_FACTRA,
													  DOC_ETAT,
													  DOC_EN_TTC,
													  DOC_REFPCF,
													  DOC_MEMO,
													  DOC_NUMERO,
													  DOC_DATE,
													  DOC_DT_PRV,
													  DOC_DTCRE,
													  DOC_DTMAJ,
													  PCF_CODE,
													  PCF_PAYEUR,
													  DOC_PIECE,
													  PAY_CODE,
													  DOC_F_RS,
													  DOC_F_RS2,
													  DOC_F_RUE,
													  DOC_F_COMP,
													  DOC_F_CP,
													  DOC_F_VILL,
													  DOC_F_CBAR,
													  DOC_L_RS,
													  DOC_L_RS2,
													  DOC_L_RUE,
													  DOC_L_COMP,
													  DOC_L_CP,
													  DOC_L_VILL,
													  DOC_L_PAYS,
													  DOC_L_CBAR,
													  REG_CODE,
													  REP_CODE,
													  NAT_CODE,
													  DEP_CODE,
													  TAR_CODE,
													  PRJ_CODE,
													  TRP_CODE,
													  DOC_CPORT,
													  DOC_PORT,
													  DOC_PPORT,
													  DOC_CFRAIS,
													  DOC_FRAIS,
													  DOC_CSUPPL,
													  DOC_SUPPL,
													  DOC_ACPTE,
													  DOC_TX_ESC,
													  PCF_REMMIN,
													  DOC_TXRFAC,
													  DOC_REMFAC,
													  DOC_USRCRE,
													  DOC_USRMAJ,
													  DOC_TRTCRE,
													  DOC_CONTRME,
													  DEV_CODE,
													  DOC_TX_DEV,
													  DOC_BRUT,
													  DOC_MT_HT,
													  DOC_MT_TVA,
													  DOC_MT_TTC,
													  DOC_MT_NET,
													  DOC_TVA_B1,
													  DOC_TVA_T1,
													  DOC_TVA_C1,
													  DOC_POIDSB,
													  DOC_POIDSN, 
													  DOC_NCOLIS,
													  DOC_VOLUME) VALUES ('
			.'\'V\',' //DOC_TYPE
			.'\'C\',' //DOC_STYPE
			.'\'\',' //DOC_RPIECE
			.'0,'//DOC_FACTRA
			.'\'E\',' //DOC_ETAT
			.'0,' //DOC_EN_TTC
			.'\'Commande Web : '.$currentOrder['invoice_number'].' \','//DOC_REFPCF
			.'\'\',' //DOC_MEMO
			.'\''.$DocNumero.'\',' //DOC_NUMERO
			.'\'' . Utility::getNoZeroDate($currentOrder['invoice_date']) . '\','//DOC_DATE -- A voir !!!
			.'\'' . Utility::getNoZeroDate($currentOrder['invoice_date']) . '\','//DOC_DT_PRV -- A voir !!!
			.'GETDATE(),' //DOC_DTCRE
			.'GETDATE(),' //DOC_DTMAJ
			.$CodeClient.','//.'\'W'.$currentOrder['id_customer'].'\',' //PCF_CODE
			.$CodeClient.','//.'\'W'.$currentOrder['id_customer'].'\',' //PCF_PAYEUR
			.'\''.$DocPiece.'\',' //DOC_PIECE
			.'\'FR\',' //PAY_CODE
			.'\'\',' //DOC_F_RS
			.'\'\',' //DOC_F_RS2
			.'\' ' . preg_replace('/\'/','\'\'',$currentOrder['invoice_address']['address1'])  . ' \',' //DOC_F_RUE
			.'\'\',' //DOC_F_COMP
		   	.'\' '. preg_replace('/\'/','\'\'',$currentOrder['delivery_address']['postcode']) . ' \',' //DOC_F_CP
			.'\' ' . preg_replace('/\'/','\'\'',$currentOrder['delivery_address']['city']) . '\',' //DOC_F_VILL
			.'\'\',' //DOC_F_CBAR
			.'\'\',' //DOC_L_RS
			.'\'\',' //DOC_L_RS2
			.'\''  . preg_replace('/\'/','\'\'',$currentOrder['delivery_address']['address1']) . '\',' //DOC_L_RUE
			.'\'\',' //DOC_L_COMP
			.'\'' . preg_replace('/\'/','\'\'',$currentOrder['delivery_address']['postcode'])  . '\',' //DOC_L_CP
			.'\' ' . preg_replace('/\'/','\'\'',$currentOrder['delivery_address']['city'])  . ' \',' //DOC_L_VILL
			.'\'FR\',' //DOC_L_PAYS
			.'\'\',' //DOC_L_CBAR
			.'\'COMPT\',' //REG_CODE
			.'\'\',' //REP_CODE
			.'\'001\',' //NAT_CODE
			.'\'001\',' //DEP_CODE
			.'\'WEB\',' //TAR_CODE
			.'\'\',' //PRJ_CODE
			.'\'\',' //TRP_CODE
			.'\'\',' //DOC_CPORT
			.'0,' //DOC_PORT
			.'\'\',' //DOC_PPORT
			.'\'\',' //DOC_CFRAIS
			.'0,' //DOC_FRAIS
			.'\'\',' //DOC_CSUPPL
			.'0,' //DOC_SUPPL
			.'0,' //DOC_ACPTE
			.'0,' //DOC_TX_ESC
			.'0,' //PCF_REMMIN
			.'0,' //DOC_TXRFAC
	        .'0,' //DOC_REMFAC
			.'\'WEB\',' //DOC_USRCRE
			.'\'WEB\',' //DOC_USRMAJ
			.'\'IMP\',' //DOC_TRTCRE
			.'0,' //DOC_CONTRME
			.'\'EUR\',' //DEV_CODE
			.'1,' //DOC_TX_DEV
			.$currentOrder['total_paid_tax_excl'].',' //DOC_BRUT
			.$currentOrder['total_paid_tax_excl'].',' //DOC_MT_HT
			.$Tva.',' //DOC_MT_TVA
			.$currentOrder['total_paid_tax_incl'].',' //DOC_MT_TTC
			.$currentOrder['total_paid_tax_incl'].',' //DOC_MT_NET
			.$currentOrder['total_paid_tax_excl'].',' //DOC_TVA_B1
			.'19.6,' //DOC_TVA_T1
			.'\'F\',' //DOC_TVA_C1
			.$currentOrder['total_products_wt'].',' //DOC_POIDSB
			.$currentOrder['total_products_wt'].',' //DOC_POIDSN 
			.'0,' //DOC_NCOLIS
			.'0)' //DOC_VOLUME
			//.' EXEC dbo.CreatEcheances @DocNumero '
			) or die ("<p>" . odbc_errormsg() . "</p>");  
			
			odbc_close($this->sqlServerConnection); 
		}
	}
}