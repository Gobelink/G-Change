-- =============================================
-- Create basic stored procedure template
-- =============================================

-- Drop stored procedure if it already exists
IF EXISTS (
  SELECT * 
    FROM INFORMATION_SCHEMA.ROUTINES 
   WHERE SPECIFIC_SCHEMA = N'dbo'
     AND SPECIFIC_NAME = N'CreatEcheances' 
)
   DROP PROCEDURE dbo.CreatEcheances
GO
-- DELETE FROM DOC_ECH WHERE DOC_NUMERO = '0000000994' 
-- DELETE FROM ECHEANCES WHERE DOC_NUMERO = '0000000994' 
-- EXEC dbo.CreatEcheances
/* 
SELECT
*
FROM DOC_ECH
WHERE DOC_NUMERO = '0000000680'
*/
CREATE PROCEDURE dbo.CreatEcheances @DocNumero VARCHAR(10)
AS
 
 /* Génération du n° d'échéance */
 DECLARE @NUMERO INT
 DECLARE @EchNumero VARCHAR(10)
 EXEC G2GETNEWNUMERO 'N_ECH',1,@NUMERO OUTPUT

 SELECT @NUMERO
 SET @EchNumero = RIGHT(REPLICATE('0',10)+CAST (@NUMERO AS VARCHAR),10);

 /* Calcul de la date d'échéance */
 DECLARE @Stype VARCHAR(1)
 DECLARE @DocDate DATETIME
 DECLARE @RegCode VARCHAR(8)
 --DECLARE @DocNumero VARCHAR(10)
 SELECT 
  @DocNumero = D.DOC_NUMERO,
  @Stype = D.DOC_STYPE,
  @DocDate = D.DOC_DATE,
  @RegCode = D.REG_CODE
 FROM DOCUMENTS D 
 WHERE D.DOC_NUMERO = @DocNumero
 AND D.DOC_TYPE = 'V' 
 AND D.DOC_STYPE = 'C'

 DECLARE @NbJours INT
 DECLARE @Le1 INT
 DECLARE @Le2 INT
 DECLARE @Le3 INT
 DECLARE @Fdm INT
 DECLARE @30j INT

 IF @RegCode <> ''
 BEGIN

 SELECT 
  @NbJours = ISNULL(MR.REG_NBJ,0),
  @Le1 = ISNULL(MR.REG_LE,0),
  @Le2 = ISNULL(MR.REG_LE2,0),
  @Le3 = ISNULL(MR.REG_LE3,0),
  @Fdm = ISNULL(CAST(MR.REG_FDM AS INT),0),
  @30j = ISNULL(CAST(MR.REG_30MOIS AS INT),0)
 FROM MODE_REG MR
 WHERE MR.REG_CODE = @RegCode

 DECLARE @DateEch DATETIME

 IF (@Le1 = 0) AND (@Le2 = 0) AND (@Le3 = 0) AND (@Fdm = 0) AND (@30j = 0) 
 BEGIN 
  SET @DateEch = DATEADD(DAY, @NbJours, @DocDate)
 END
 ELSE SET @DateEch = @DateEch + ''
  
 DECLARE @DateEchInt DATETIME
 DECLARE @NbJMois INT

 IF (@Fdm = 1)
 BEGIN 
  SET @DateEchInt = DATEADD(DAY, @NbJours, @DocDate)
  SET @NbJMois = DAY(DATEADD (m, 1, DATEADD (d, 1 - DAY(@DateEchInt), @DateEchInt)) - 1)
  SET @DateEch = CAST(@NbJMois AS VARCHAR(2))+'/'+RIGHT(REPLICATE('0',2)+CAST(MONTH(@DateEchInt) AS VARCHAR(2)),2)+'/'+CAST(YEAR(@DateEchInt) AS VARCHAR(4))
 END
 ELSE SET @DateEch = @DateEch + ''

 IF (@Fdm = 1) AND (@Le1 <> 0)
 BEGIN 
  SET @DateEchInt = DATEADD(DAY, @NbJours, @DocDate)
  SET @NbJMois = DAY(DATEADD (m, 1, DATEADD (d, 1 - DAY(@DateEchInt), @DateEchInt)) - 1)
  SET @DateEch = CAST(CAST(@NbJMois AS VARCHAR(2))+'/'+RIGHT(REPLICATE('0',2)+CAST(MONTH(@DateEchInt) AS VARCHAR(2)),2)+'/'+CAST(YEAR(@DateEchInt) AS VARCHAR(4)) AS DATETIME)
 END
 ELSE SET @DateEch = @DateEch + ''

 IF (@Fdm = 0) AND (@Le1 <> 0)
 BEGIN 
   SET @DateEch = DATEADD(DAY, @NbJours+@Le1, @DocDate)
 END
 ELSE SET @DateEch = @DateEch + ''

 IF (@Fdm = 1) AND (@Le1 <> 0)
 BEGIN 
  SET @DateEchInt = DATEADD(DAY, @NbJours, @DocDate)
  SET @NbJMois = DAY(DATEADD (m, 1, DATEADD (d, 1 - DAY(@DateEchInt), @DateEchInt)) - 1)
  SET @DateEch = DATEADD(DAY,@Le1,CAST(CAST(@NbJMois AS VARCHAR(2))+'/'+RIGHT(REPLICATE('0',2)+CAST(MONTH(@DateEchInt) AS VARCHAR(2)),2)+'/'+CAST(YEAR(@DateEchInt) AS VARCHAR(4)) AS DATETIME))
 END
 ELSE SET @DateEch = @DateEch + ''

 SELECT @DateEch
 /* Insertion des échéances */

 
    INSERT INTO DOC_ECH (DOC_NUMERO
      ,ECH_ORDER
      ,ECH_NUMERO
      ,DOC_STYPE
      ,DOC_PIECE
      ,ECH_LIB
      ,REG_CODE
      ,ECH_PCENT
      ,ECH_SUR_HT
      ,ECH_MT
      ,ECH_LIVRE
      ,ECH_DATE
      ,ECH_ACOMPT
      ,ECH_RETENU)
	SELECT 
	 D.DOC_NUMERO,
	 '00001',
	 @EchNumero,
	 CASE WHEN D.DOC_STYPE IN ('B','C','A','0') THEN 'F' ELSE D.DOC_STYPE END,
	 '',
	 D.DOC_REFPCF,
	 D.REG_CODE,
	 100,
	 0,
	 D.DOC_MT_TTC,
	 0,
	 @DateEch,
	 0,
	 0
	FROM DOCUMENTS D
	WHERE D.DOC_NUMERO = @DocNumero
	AND D.DOC_NUMERO NOT IN (SELECT DE.DOC_NUMERO FROM DOC_ECH DE)
	
	IF @Stype = 'F' OR @Stype = 'A' OR @Stype = '0' OR @Stype = '1'
	BEGIN
	INSERT INTO ECHEANCES
           (ECH_NUMERO
           ,DOC_NUMERO
           ,ECR_NUMERO
           ,PCF_CODE
           ,PCF_RS
           ,ECH_LIB
           ,ECH_PIECE
           ,ECH_DTEMIS
           ,ECH_DATE
           ,REG_CODE
           ,ECH_DT_IMP
           ,ECH_IMPAYE
           ,DEV_CODE
           ,ECH_ARECEV
           ,ECH_RECU
           ,ECH_D_AREC
           ,ECH_D_REC
           ,ECH_E_AREC
           ,ECH_E_REC
           ,ECH_APAYER
           ,ECH_PAYER
           ,ECH_D_ADEP
           ,ECH_D_DEP
           ,ECH_E_ADEP
           ,ECH_E_DEP
           ,ECH_SOLDER
           ,ECH_NO_DOC
           ,ECH_ETAT
           ,ECH_NBREL
           ,ECH_DTMAJ
           ,ECH_USRMAJ
           ,ECH_NUMMAJ)
     SELECT
	  /* ECH_NUMERO - N° Echéances */
	  @EchNumero,
	  /* DOC_NUMERO - N° Documents */
	  D.DOC_NUMERO,
	  /* ECR_NUMERO - N° Ecritures */
	  NULL,
	  /* PCF_CODE - Code Tiers */
	  D.PCF_CODE,
	  /* PCF_RS - RS Tiers */
	  T.PCF_RS,
	  /* ECH_LIB - Libellé échéances */
	  D.DOC_REFPCF,
	  /* ECH_PIECE - N° Pièce */
	  D.DOC_PIECE,
	  /* ECH_DTEMIS - Date du doc */
	  D.DOC_DATE,
	  /* ECH_DATE - Date d'échéances */
	  @DateEch,
	  /* REG_CODE - Mode de règlement */
	  D.REG_CODE,
	  /* ECH_DT_IMP - Date impayé */
	  NULL,
	  /* ECH_IMPAYE - Impayé */
	  NULL,
	  /* DEV_CODE - Code de la devise */
	  D.DEV_CODE,
	  /* ECH_ARECEV - Mt à recevoir */
	  CASE 
	   WHEN D.DOC_TYPE = 'V' 
	   THEN 
	    CASE 
		 WHEN D.DOC_STYPE IN ('F','1')
		 THEN D.DOC_MT_TTC
		 ELSE 0
		END
	   WHEN D.DOC_TYPE = 'A'
	   THEN 
	    CASE 
		 WHEN D.DOC_STYPE IN ('A','0')
		 THEN D.DOC_MT_TTC
		 ELSE 0
		END
	  END,
	  /* ECH_RECU - Mt reçu */
	  0,
	  /* ECH_D_AREC - Mt à recevoir en devise */
	  CASE 
	   WHEN D.DOC_TYPE = 'V' 
	   THEN 
	    CASE 
		 WHEN D.DOC_STYPE IN ('F','1')
		 THEN 
		   CASE -- Devise <> euro -- 
			 WHEN D.DEV_CODE <> 'EUR'
			 THEN
				CASE -- Devise à l'incertain -- 
				 WHEN DE.DEV_INCERT = 1 
				 THEN D.DOC_MT_TTC
				 * ISNULL(D.DOC_TX_DEV,1) 
				 ELSE  -- Devise au certain -- 
				 D.DOC_MT_TTC
				 /CASE 
					WHEN ISNULL(D.DOC_TX_DEV,0)<>0 
					THEN ISNULL(D.DOC_TX_DEV,1) 
					ELSE 1 
				  END 
				END
 			 ELSE -- Devise en euro -- 
				D.DOC_MT_TTC
			END 
		 ELSE 0
		END
	   WHEN D.DOC_TYPE = 'A'
	   THEN 
	    CASE 
		 WHEN D.DOC_STYPE IN ('A','0')
		 THEN 
		  CASE -- Devise <> euro -- 
			 WHEN D.DEV_CODE <> 'EUR'
			 THEN
				CASE -- Devise à l'incertain -- 
				 WHEN DE.DEV_INCERT = 1 
				 THEN D.DOC_MT_TTC
				 * ISNULL(D.DOC_TX_DEV,1) 
				 ELSE  -- Devise au certain -- 
				 D.DOC_MT_TTC
				 /CASE 
					WHEN ISNULL(D.DOC_TX_DEV,0)<>0 
					THEN ISNULL(D.DOC_TX_DEV,1) 
					ELSE 1 
				  END 
				END
 			 ELSE -- Devise en euro -- 
				D.DOC_MT_TTC
			END 
		 ELSE 0
		END
	  END,
	  /* ECH_D_REC - Mt reçu en devise */
	  0,
	  /* ECH_E_AREC - Mt à recevoir en euro */
	  CASE 
	   WHEN D.DOC_TYPE = 'V' 
	   THEN 
	    CASE 
		 WHEN D.DOC_STYPE IN ('F','1')
		 THEN 
		   CASE -- Devise <> euro -- 
			 WHEN D.DEV_CODE <> 'EUR'
			 THEN
				CASE -- Devise à l'incertain -- 
				 WHEN DE.DEV_INCERT = 1 
				 THEN D.DOC_MT_TTC
				 * ISNULL(D.DOC_TX_DEV,1) 
				 ELSE  -- Devise au certain -- 
				 D.DOC_MT_TTC
				 /CASE 
					WHEN ISNULL(D.DOC_TX_DEV,0)<>0 
					THEN ISNULL(D.DOC_TX_DEV,1) 
					ELSE 1 
				  END 
				END
 			 ELSE -- Devise en euro -- 
				D.DOC_MT_TTC
			END 
		 ELSE 0
		END
	   WHEN D.DOC_TYPE = 'A'
	   THEN 
	    CASE 
		 WHEN D.DOC_STYPE IN ('A','0')
		 THEN 
		   CASE -- Devise <> euro -- 
			 WHEN D.DEV_CODE <> 'EUR'
			 THEN
				CASE -- Devise à l'incertain -- 
				 WHEN DE.DEV_INCERT = 1 
				 THEN D.DOC_MT_TTC
				 * ISNULL(D.DOC_TX_DEV,1) 
				 ELSE  -- Devise au certain -- 
				 D.DOC_MT_TTC
				 /CASE 
					WHEN ISNULL(D.DOC_TX_DEV,0)<>0 
					THEN ISNULL(D.DOC_TX_DEV,1) 
					ELSE 1 
				  END 
				END
 			 ELSE -- Devise en euro -- 
				D.DOC_MT_TTC
			END 
		 ELSE 0
		END
	  END,
	  /* ECH_E_REC - Mt reçu en euro */
	  0,
	  /*ECH_APAYER - Mt à payer */
	  CASE 
	   WHEN D.DOC_TYPE = 'V' 
	   THEN 
	    CASE 
		 WHEN D.DOC_STYPE IN ('A','0')
		 THEN D.DOC_MT_TTC
		 ELSE 0
		END
	   WHEN D.DOC_TYPE = 'A'
	   THEN 
	    CASE 
		 WHEN D.DOC_STYPE IN ('F','1')
		 THEN D.DOC_MT_TTC
		 ELSE 0
		END
	  END,
	  /* ECH_PAYER - Mt payé */
	  0,
	  /* ECH_D_ADEP - Mt à paye en devise */
      CASE 
	   WHEN D.DOC_TYPE = 'V' 
	   THEN 
	    CASE 
		 WHEN D.DOC_STYPE IN ('A','0')
		 THEN 
		   CASE -- Devise <> euro -- 
			 WHEN D.DEV_CODE <> 'EUR'
			 THEN
				CASE -- Devise à l'incertain -- 
				 WHEN DE.DEV_INCERT = 1 
				 THEN D.DOC_MT_TTC
				 * ISNULL(D.DOC_TX_DEV,1) 
				 ELSE  -- Devise au certain -- 
				 D.DOC_MT_TTC
				 /CASE 
					WHEN ISNULL(D.DOC_TX_DEV,0)<>0 
					THEN ISNULL(D.DOC_TX_DEV,1) 
					ELSE 1 
				  END 
				END
 			 ELSE -- Devise en euro -- 
				D.DOC_MT_TTC
			END 
		 ELSE 0
		END
	   WHEN D.DOC_TYPE = 'A'
	   THEN 
	    CASE 
		 WHEN D.DOC_STYPE IN ('F','1')
		 THEN 
		  CASE -- Devise <> euro -- 
			 WHEN D.DEV_CODE <> 'EUR'
			 THEN
				CASE -- Devise à l'incertain -- 
				 WHEN DE.DEV_INCERT = 1 
				 THEN D.DOC_MT_TTC
				 * ISNULL(D.DOC_TX_DEV,1) 
				 ELSE  -- Devise au certain -- 
				 D.DOC_MT_TTC
				 /CASE 
					WHEN ISNULL(D.DOC_TX_DEV,0)<>0 
					THEN ISNULL(D.DOC_TX_DEV,1) 
					ELSE 1 
				  END 
				END
 			 ELSE -- Devise en euro -- 
				D.DOC_MT_TTC
			END 
		 ELSE 0
		END
	  END,
	  /* ECH_D_DEP - Mt payé en devise */
	  0,
	  /* ECH_E_ADEP - Mt à payer en euros */
      CASE 
	   WHEN D.DOC_TYPE = 'V' 
	   THEN 
	    CASE 
		 WHEN D.DOC_STYPE IN ('A','0')
		 THEN 
		   CASE -- Devise <> euro -- 
			 WHEN D.DEV_CODE <> 'EUR'
			 THEN
				CASE -- Devise à l'incertain -- 
				 WHEN DE.DEV_INCERT = 1 
				 THEN D.DOC_MT_TTC
				 * ISNULL(D.DOC_TX_DEV,1) 
				 ELSE  -- Devise au certain -- 
				 D.DOC_MT_TTC
				 /CASE 
					WHEN ISNULL(D.DOC_TX_DEV,0)<>0 
					THEN ISNULL(D.DOC_TX_DEV,1) 
					ELSE 1 
				  END 
				END
 			 ELSE -- Devise en euro -- 
				D.DOC_MT_TTC
			END 
		 ELSE 0
		END
	   WHEN D.DOC_TYPE = 'A'
	   THEN 
	    CASE 
		 WHEN D.DOC_STYPE IN ('F','1')
		 THEN 
		    CASE -- Devise <> euro -- 
			 WHEN D.DEV_CODE <> 'EUR'
			 THEN
				CASE -- Devise à l'incertain -- 
				 WHEN DE.DEV_INCERT = 1 
				 THEN D.DOC_MT_TTC
				 * ISNULL(D.DOC_TX_DEV,1) 
				 ELSE  -- Devise au certain -- 
				 D.DOC_MT_TTC
				 /CASE 
					WHEN ISNULL(D.DOC_TX_DEV,0)<>0 
					THEN ISNULL(D.DOC_TX_DEV,1) 
					ELSE 1 
				  END 
				END
 			 ELSE -- Devise en euro -- 
				D.DOC_MT_TTC
			END 
		 ELSE 0
		END
	  END,
	  /* ECH_E_DEP - Mt payé en euros */
	  0,
	  /* ECH_SOLDER - Echéances soldée */
	  0,
	  /* ECH_NO_DOC - Echéance sans document*/
      0,
	  /* ECH_ETAT - Etat de l'échéance */
      'E',
	  /* ECH_NBREL - Nbre de rélance */
	  0,
	  /* ECH_DTMAJ - Date de màj */
      GETDATE(),
	  /* ECH_USRMAJ - Utilisateur de màj */
	  'GESTIMUM',
	  /* ECH_NUMMAJ - Nb de màj */
	  1
	 FROM DOCUMENTS D
	  INNER JOIN TIERS T ON T.PCF_CODE = D.PCF_CODE
	  LEFT OUTER JOIN DEVISES DE ON DE.DEV_CODE = D.DEV_CODE
	 WHERE D.DOC_NUMERO = @DocNumero
	END
  END
GO

