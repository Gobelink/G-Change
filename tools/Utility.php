<?php 
class Utility{
	function __construct(){

	}

	public static function getNoZeroDate($dateString){
		// This method makes sure that no 0-date is given from Prestashop, because SQLServer does not allow it
		// -0001-11-30 00:00:00 is returned by DateTime::format('0000-00-00 00:00:00')
		if($dateString == '0000-00-00 00:00:00'
			|| $dateString == '-0001-11-30 00:00:00' ){
			return NULL; // This date is the Gestimum default "no date" date
		}
		return $dateString;
	}

	public static function getOrPrestashopQueryStringFromArray($theArray){
		
		$idsQueryString = '';

		foreach ($theArray as $key => $value) {
			$idsQueryString = $idsQueryString . '|' . $value;
		}
		return trim($idsQueryString, '|');
	}

	public static function getString()
	{
		return '
		<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
			<products>
				<product>
					<id>10</id>
					<id_manufacturer/>
					<id_supplier/>
					<id_category_default/>
					<new/>
					<cache_default_attribute>
						0
					</cache_default_attribute>
					<id_default_image/>
					<id_default_combination/>
					<id_tax_rules_group/>
					<position_in_category not_filterable="true">
						0
					</position_in_category>
					<manufacturer_name/>
					<quantity not_filterable="true">
						0
					</quantity>
					<type not_filterable="true">
						simple
					</type>
					<id_shop_default>
						1
					</id_shop_default>
					<reference>
						demo_xxx
					</reference>
					<supplier_reference/>
					<location/>
					<width>
						0.000000
					</width>
					<height>
						0.000000
					</height>
					<depth>
						0.000000
					</depth>
					<weight>
						0.500000
					</weight>
					<quantity_discount>
						0
					</quantity_discount>
					<ean13>
						0
					</ean13>
					<upc/>
					<cache_is_pack>
						0
					</cache_is_pack>
					<cache_has_attachments>
						0
					</cache_has_attachments>
					<is_virtual>
						0
					</is_virtual>
					<on_sale>
						0
					</on_sale>
					<online_only>
						0
					</online_only>
					<ecotax>
						0.000000
					</ecotax>
					<minimal_quantity>
						1
					</minimal_quantity>
					<price>
						199
					</price>
					<wholesale_price>
						70.000000
					</wholesale_price>
					<unity/>
					<unit_price_ratio>
						0.000000
					</unit_price_ratio>
					<additional_shipping_cost>
						0.00
					</additional_shipping_cost>
					<customizable>
						0
					</customizable>
					<text_fields>
						0
					</text_fields>
					<uploadable_files>
						0
					</uploadable_files>
					<active>
						1
					</active>
					<redirect_type/>
					<id_product_redirected>
						0
					</id_product_redirected>
					<available_for_order>
						1
					</available_for_order>
					<available_date>
						0000-00-00
					</available_date>
					<condition>
						new
					</condition>
					<show_price>
						1
					</show_price>
					<indexed>
						1
					</indexed>
					<visibility>
						both
					</visibility>
					<advanced_stock_management>
						0
					</advanced_stock_management>
					<date_add>
						2013-08-01 16:33:38
					</date_add>
					<date_upd>
						2013-08-01 16:33:38
					</date_upd>
					<meta_description/>
					<meta_keywords/>
					<meta_title/>
					<link_rewrite>
						<language id="1" xlink:href="http://localhost/prestashop/api/languages/1">
							ipod-nano
						</language>
					</link_rewrite>
					<name>
						<language id="1" xlink:href="http://localhost/prestashop/api/languages/1">
							iPod Nano
						</language>
					</name>
					<description>
						<language id="1" xlink:href="http://localhost/prestashop/api/languages/1">
							Des courbes avantageuses. Pour les amateurs de sensations, voici neuf nouveaux coloris. Et ce n\'est pas tout ! Faites l\'experience du design elliptique en aluminum et verre. Vous ne voudrez plus le lacher. Beau et intelligent. La nouvelle fonctionnalite Genius fait d\'iPod nano votre DJ personnel. Genius cree des listes de lecture en recherchant dans votre bibliotheque les chansons qui vont bien ensemble. Fait pour bouger avec vous. iPod nano est equipe de l\'accelerometre. Secouez-le pour melanger votre musique. Basculez-le pour afficher Cover Flow. Et decouvrez des jeux adaptes a vos mouvements.
						</language>
					</description>
					<description_short>
						<language id="1" xlink:href="http://localhost/prestashop/api/languages/1">
							Nouveau design. Nouvelles fonctionnalites. Desormais en 8 et 16 Go. iPod nano, plus rock que jamais.
						</language>
					</description_short>
					<available_now>
						<language id="1" xlink:href="http://localhost/prestashop/api/languages/1">En stock</language>
					</available_now>
					<available_later/>
					<associations>
						<categories node_type="category"/>
						<images node_type="image"/>
						<combinations node_type="combinations"/>
					</associations>
				</product>
			</products>
		</prestashop>';
	}
}