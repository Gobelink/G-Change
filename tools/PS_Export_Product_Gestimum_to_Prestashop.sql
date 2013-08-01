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
 MAX(A.XXX_DECLIN) AS 'declinaison',
 MAX(A.ART_CODE) AS 'reference',
 MAX(A.XXX_IDCATE) AS 'id_category',
 MAX(A.XXX_IDDECL) AS 'id_declinaison',
 MAX(A.ART_LONG) AS 'width',
 MAX(A.ART_LARG) AS 'height',
 MAX(A.ART_POIDSB) AS 'weight',
 MAX(A.ART_CBAR) AS 'ean13',
 MAX(A.ART_QTEDFT) AS 'minimal_quantity',
 MAX(A.ART_DTCREE) AS 'date_add',
 MAX(A.ART_DTMAJ) AS 'date_upd',
 MAX(A.ART_LIB) AS 'name', 
 ISNULL((SELECT SUM(ST.STK_REEL) FROM ART_STOCK ST WHERE ST.ART_CODE = MAX(A.ART_CODE)),0) AS 'stock_available',
 ISNULL((SELECT LP.PRM_PRIX FROM LPROMOS LP INNER JOIN PROMOS P ON P.PRM_CODE = LP.PRM_CODE WHERE LP.ART_CODE = MAX(A.ART_CODE) AND DATEDIFF(DAY,P.PRM_DT_FIN, GETDATE()) <= 0),0) AS 'PrixPromo',
 ISNULL((SELECT LG.TAR_PRIX FROM TGRILLES LG WHERE LG.ART_CODE = MAX(A.ART_CODE) AND LG.TAR_CODE = 'WEB'),0) AS 'PrixGrille',
 MAX(ISNULL(A.ART_P_VTE,0)) AS 'PrixArticle'
FROM ARTICLES A
WHERE ISNULL(A.XXX_ORIGIN,'') = @SiteDeSynchro
GROUP BY A.XXX_IDPRES

GO