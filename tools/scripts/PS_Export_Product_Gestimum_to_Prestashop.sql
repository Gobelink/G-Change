-- =============================================
-- Create basic stored procedure template
-- =============================================

-- Drop stored procedure if it already exists
IF EXISTS (
  SELECT * 
    FROM INFORMATION_SCHEMA.ROUTINES 
   WHERE SPECIFIC_SCHEMA = N'dbo'
     AND SPECIFIC_NAME = N'Presta_Synchro_Get_Articles_For_Creation' 
)
   DROP PROCEDURE dbo.Presta_Synchro_Get_Articles_For_Creation 
GO

/* Numero des sites : 
-> 1 = Site catalogue
-> 2 = Site e-commerce */

USE [AGRIINDUS_TP]
GO
/****** Object:  StoredProcedure [dbo].[Presta_Synhro_Article_Site_Catalogue]    Script Date: 06/08/2013 10:34:39 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

/* Numero des sites : 
-> 1 = Site catalogue
-> 2 = Site e-commerce */

/* La procédure qui récupère les produits à insérer */
ALTER PROCEDURE [dbo].[Presta_Synchro_Get_Articles_For_Creation] 
(@SiteDeSynchro INT)

AS

SELECT TOP 10
	ISNULL(A.ART_CODE,0) AS 'id_product',
	/*ISNULL(A.XXX_ORIGIN,0) AS 'origin',
	ISNULL(A.XXX_DECLIN,0) AS 'declinaison',
	ISNULL(A.XXX_IDDECL,0) AS 'id_declinaison',
	ISNULL(A.ART_CODE,0) AS 'reference', 
	ISNULL(A.ART_DTCREE,0) AS 'date_add',
	ISNULL(A.ART_DTMAJ,0) AS 'date_upd',*/
	ISNULL(A.XXX_IDCATE,0) AS 'id_category',
	ISNULL(A.ART_LONG,0) AS 'width',
	ISNULL(A.ART_LARG,0) AS 'height',
	ISNULL(A.ART_POIDSB,0) AS 'weight',
	ISNULL(A.ART_CBAR,0) AS 'ean13',
	ISNULL(A.ART_QTEDFT,0) AS 'minimal_quantity',
	ISNULL(A.ART_LIB,0) AS 'name', 
	ISNULL((SELECT SUM(ISNULL(ST.STK_REEL,0)) FROM ART_STOCK ST WHERE ST.ART_CODE = A.ART_CODE),0) AS 'stock_available',
	ISNULL((SELECT LP.PRM_PRIX FROM LPROMOS LP INNER JOIN PROMOS P ON P.PRM_CODE = LP.PRM_CODE WHERE LP.ART_CODE = A.ART_CODE AND DATEDIFF(DAY,P.PRM_DT_FIN, GETDATE()) <= 0),0) AS 'PrixPromo',
	ISNULL((SELECT LG.TAR_PRIX FROM TGRILLES LG WHERE LG.ART_CODE = A.ART_CODE AND LG.TAR_CODE = 'WEB'),0) AS 'PrixGrille',
	ISNULL(A.ART_P_VTE,0) AS 'PrixArticle'
FROM ARTICLES A
WHERE ISNULL(A.XXX_ORIGIN,0) <> @SiteDeSynchro
	AND ISNULL(CASE WHEN @SiteDeSynchro = 1 THEN A.XXX_S1CSYN ELSE (CASE WHEN @SiteDeSynchro = 2 THEN A.XXX_S2CSYN END) END, 0) = 1
	AND ISNULL(CASE WHEN @SiteDeSynchro = 1 THEN A.XXX_S1DSYN ELSE (CASE WHEN @SiteDeSynchro = 2 THEN A.XXX_S2DSYN END) END, 0) = 0
GO