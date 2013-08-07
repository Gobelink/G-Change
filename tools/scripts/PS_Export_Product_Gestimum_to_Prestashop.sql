-- =============================================
-- Create basic stored procedure template
-- =============================================

-- Drop stored procedure if it already exists
IF EXISTS (
  SELECT * 
    FROM INFORMATION_SCHEMA.ROUTINES 
   WHERE SPECIFIC_SCHEMA = N'dbo'
     AND SPECIFIC_NAME = N'Presta_Get_Products' 
)
   DROP PROCEDURE dbo.Presta_Get_Products 
GO

/* Numero des sites : 
-> 1 = Site catalogue
-> 2 = Site e-commerce */

CREATE PROCEDURE dbo.Presta_Get_Products 
(@SiteDeSynchro INT)

AS

SELECT
 A.XXX_IDPRES AS 'id_product',
 A.XXX_DECLIN AS 'declinaison',
 A.ART_CODE AS 'reference',
 A.XXX_IDCATE AS 'id_category',
 CASE WHEN ISNULL(GV.GAV_CODE, '') <> '' THEN GV.GAV_CODE ELSE A.XXX_IDDECL END AS 'id_declinaison',
 A.ART_LONG AS 'width',
 A.ART_LARG AS 'height',
 A.ART_POIDSB AS 'weight',
 A.ART_CBAR AS 'ean13',
 A.ART_QTEDFT AS 'minimal_quantity',
 A.ART_DTCREE AS 'date_add',
 A.ART_DTMAJ AS 'date_upd',
 A.ART_LIB AS 'name', 
 ISNULL((SELECT SUM(ST.STK_REEL) FROM ART_STOCK ST WHERE ST.ART_CODE = A.ART_CODE),0) AS 'stock_available',
 ISNULL((SELECT LP.PRM_PRIX FROM LPROMOS LP INNER JOIN PROMOS P ON P.PRM_CODE = LP.PRM_CODE WHERE LP.ART_CODE = A.ART_CODE AND DATEDIFF(DAY,P.PRM_DT_FIN, GETDATE()) <= 0),0) AS 'PrixPromo',
 ISNULL((SELECT LG.TAR_PRIX FROM TGRILLES LG WHERE LG.ART_CODE = A.ART_CODE AND LG.TAR_CODE = 'WEB'),0) AS 'PrixGrille',
 ISNULL(A.ART_P_VTE,0) AS 'PrixArticle'
FROM ARTICLES A
 LEFT OUTER JOIN GAMMES G ON G.GAM_CODE = A.ART_TGAMME
 LEFT OUTER JOIN GAM_ELTS GE ON GE.GAE_CODE = G.GAM_COMPO
 LEFT OUTER JOIN GAM_VALOR GV ON GV.GAE_CODE = GE.GAE_CODE
WHERE ISNULL(A.XXX_ORIGIN,'') = @SiteDeSynchro AND  ISNULL(A.XXX_ORIGIN,'') <> ''

GO