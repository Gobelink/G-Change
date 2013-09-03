<?php
class OrdersConstants{
 public static function getOrdersLinesInsertionString(
                $DocNumero,
                $lineNumber,
                $productAttributeId,
                $productName,
                $productQuantity,
                $productPrice,
                $invoiceDate,
                $priceOfQuantity,
                $DocPiece
                ){
                return 'INSERT INTO LIGNES (DOC_NUMERO, -- 1
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
                                . '\''.$DocNumero. '\','//DOC_NUMERO -- 1
                                . 'RIGHT(REPLICATE(\'0\',5)+CAST('.$lineNumber. ' AS VARCHAR(5)),5),' //LIG_NUMERO -- 2
                                . '00000,' //LIG_SUBNUM -- 3
                                . '001,' // DEP_CODE -- 4
                                . '0,' //LIG_NLOT -- 5
                                . '\'P\',' //LIG_TYPE -- 6
                                . '\''. $productAttributeId . '\',' // ART_CODE -- 7
                                . '\'\',' //ART_TGAMME -- 8
                                . '\'\',' //ART_GAMME -- 9 Id_declinaison
                                . '\'\',' //ART_REFFRS -- 10
                                . '\'\',' //ART_REFCLI -- 11
                                . '\''. preg_replace('/\'/','\'\'',$productName) . '\',' //LIG_LIB -- 12
                                . $productQuantity . ',' //LIG_QTE -- 13
                                . $productPrice . ',' //LIG_P_BRUT -- 14
                                . '\'\',' //LIG_REMISE -- 15
                                . $productPrice . ',' //LIG_P_NET -- 16
                                . $priceOfQuantity . ',' //LIG_TOTAL -- 17
                                . $productQuantity. ',' //LIG_Q_CMDE -- 18
                                . '0,' //LIG_Q_REL -- 19
                                . '0,' //LIG_Q_LIVR -- 20
                                . '0,' //LIG_Q_FACT -- 21 
                                . $productPrice. ',' //LIG_P_BASE -- 22
                                . '0,' //LIG_FRENDU -- 23
                                . '\'\',' //LIG_GP -- 24
                                . '\'\',' //PRM_CODE -- 25
                                . '0,' //LIG_FRAPP -- 26
                                . '0,' //LIG_DOUANE -- 27
                                . '0,' //LIG_COEF -- 28
                                . '0,' //LIG_POIDSB -- 29
                                . '0,' //LIG_POIDST -- 30
                                . '0,' //LIG_POIDSN -- 31
                                . '0,' //LIG_POIDS -- 32
                                . '0,' //ART_NCOLIS -- 33
                                . '0,' //LIG_NCOLIS -- 34
                                . '0,' //LIG_LONG -- 35
                                . '0,' //LIG_LARG -- 36
                                . '0,' //LIG_HAUT -- 37
                                . '0,' //LIG_SURFAC -- 38
                                . '0,' //LIG_VOLUTE -- 39
                                . '0,' //LIG_VOLUME -- 40
                                . '\'\',' //LIG_NUMLOT -- 41
                                . '\'U\',' //LIG_UC -- 42
                                . '1,'//LIG_COND -- 43
                                . '\'U\',' //LIG_UB -- 44
                                . '1,' //LIG_R_UCUV -- 45
                                . '\'M\',' //LIG_TSTOCK -- 46
                                . '\'\',' //REP_CODE -- 47
                                . '\'\',' //TAR_CODE -- 48
                                . '1,' //LIG_PRIXAU -- 49
                                . '0,' //LIG_P_PRV -- 50
                                . '0,' //LIG_COUT -- 51
                                . '0,' //LIG_FRAIS -- 52 
                                . '0,' //LIG_FRAIS2 -- 53 
                                . '0,' //LIG_FRAIS3 -- 54
                                . '0,' //PRJ_CODE -- 55
                                . '\'' . Utility::getNoZeroDate($invoiceDate) . '\','//LIG_DT_CMD -- 56
                                . '\''.$DocPiece. '\',' //LIG_N_CMD -- 57
                                . 'GETDATE(),' //LIG_DTCRE
                                . '\'WEB\',' //LIG_USRCRE
                                . 'GETDATE(),' //LIG_DTMAJ
                                . '\'WEB\',' //LIG_USRMAJ
                                . '1,' //LIG_NUMMAJ
                                . '19.6,' //NAT_TVATX
                                . '\'F\')'
								. ' UPDATE LIGNES SET ART_CODE = A.ART_CODE FROM LIGNES L INNER JOIN ARTICLES A ON CAST(A.XXX_IDPRES AS VARCHAR(50))+CAST(A.XXX_IDDECL AS VARCHAR(50)) = ' . $productAttributeId
                ;
       
        }

        public static function getOrdersDocumentsInsertionString(
                $invoiceNumber,
                $invoiceDate,
                $CodeClient,
                $DocPiece,
                $invoiceAddressOne,
                $invoiceAddressPostCode,
                $invoiceAddressCity,
                $deliveryAddressOne,
                $deliveryAddressPostCode,
                $deliveryAddressCity,
                $totalPaidTaxExcl,
                $totalPaidTaxIncl,
                $Tva,
                $totalProductsWt,
                $DocNumero
                ){
                
                return ' INSERT INTO dbo.DOCUMENTS (
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
                                                .'\'Commande Web : '.$invoiceNumber.' \','//DOC_REFPCF
                                                .'\'\',' //DOC_MEMO
                                                .'\''.$DocNumero .'\',' //DOC_NUMERO
                                                .'\'' . Utility::getNoZeroDate($invoiceDate) . '\','//DOC_DATE -- A voir !!!
                                                .'\'' . Utility::getNoZeroDate($invoiceDate) . '\','//DOC_DT_PRV -- A voir !!!
                                                .'GETDATE(),' //DOC_DTCRE
                                                .'GETDATE(),' //DOC_DTMAJ
                                                .$CodeClient.','//.'\'W'.$currentOrder['id_customer'].'\',' //PCF_CODE
                                                .$CodeClient.','//.'\'W'.$currentOrder['id_customer'].'\',' //PCF_PAYEUR
                                                .'\''.$DocPiece.'\',' //DOC_PIECE
                                                .'\'FR\',' //PAY_CODE
                                                .'\'\',' //DOC_F_RS
                                                .'\'\',' //DOC_F_RS2
                                                .'\' ' . preg_replace('/\'/','\'\'',$invoiceAddressOne)  . ' \',' //DOC_F_RUE
                                                .'\'\',' //DOC_F_COMP
                                                .'\' '. preg_replace('/\'/','\'\'',$invoiceAddressPostCode) . ' \',' //DOC_F_CP
                                                .'\' ' . preg_replace('/\'/','\'\'',$invoiceAddressCity) . '\',' //DOC_F_VILL
                                                .'\'\',' //DOC_F_CBAR
                                                .'\'\',' //DOC_L_RS
                                                .'\'\',' //DOC_L_RS2
                                                .'\''  . preg_replace('/\'/','\'\'',$deliveryAddressOne) . '\',' //DOC_L_RUE
                                                .'\'\',' //DOC_L_COMP
                                                .'\'' . preg_replace('/\'/','\'\'',$deliveryAddressPostCode)  . '\',' //DOC_L_CP
                                                .'\' ' . preg_replace('/\'/','\'\'',$deliveryAddressCity)  . ' \',' //DOC_L_VILL
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
                                                .$totalPaidTaxExcl.',' //DOC_BRUT
                                                .$totalPaidTaxExcl.',' //DOC_MT_HT
                                                .$Tva.',' //DOC_MT_TVA
                                                .$totalPaidTaxIncl.',' //DOC_MT_TTC
                                                .$totalPaidTaxIncl.',' //DOC_MT_NET
                                                .$totalPaidTaxExcl.',' //DOC_TVA_B1
                                                .'19.6,' //DOC_TVA_T1
                                                .'\'F\',' //DOC_TVA_C1
                                                .$totalProductsWt.',' //DOC_POIDSB
                                                .$totalProductsWt.',' //DOC_POIDSN 
                                                .'0,' //DOC_NCOLIS
                                                .'0)'; //DOC_VOLUME
                                                //.' EXEC dbo.CreatEcheances @DocNumero ';
        
        }

        public static function getExecG2GetNewNumeroProcedureString(){

                return 'DECLARE @NUMERO INT
                        EXEC G2GETNEWNUMERO \'N_DOC\', 1, @NUMERO OUTPUT                
                        SELECT @NUMERO';
        }

        public static function getExecG2GetNewPiece(){

                return 'DECLARE @Identifiant VARCHAR(6)
                        SET @Identifiant = \'N_VTEC\'
                 
                        DECLARE @Prefixe VARCHAR (4)
                        SET @Prefixe = LEFT((SELECT REPLACE (SP.SOC_PRMTXT, \' \', \'\') FROM SOC_PARAMS SP WHERE SP.SOC_PARAM = @Identifiant),5)
                 
                        DECLARE @Resultat VARCHAR(15)
                        EXEC G2GetNewPiece @Identifiant, @Prefixe, @Resultat OUTPUT
                        
                        SELECT @Resultat';
        }
		
		public static function GetCustomers($CodeClient) {
		 return 'SELECT T.PCF_CODE FROM TIERS T WHERE T.PCF_CODE = \'' . $CodeClient . '\'';
		}
		public static function GetProducts($productAttributeId) {
		 return 'SELECT A.ART_CODE FROM ARTICLES A WHERE A.ART_CODE = \'' . $productAttributeId . '\'';
		}
		public static function GetOrders($IdOrders) {
		 return 'SELECT D.XXX_IDORDE FROM DOCUMENTS D WHERE D.XXX_IDORDE = \'' . $IdOrders . '\'';
		}
}