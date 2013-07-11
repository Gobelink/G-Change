<?php

class productsMonkey{
	
	protected $myAdvancedManipulationEngine;

	protected $from;
	protected $to;

	function __construct($advancedManipulationEngine, $from, $to){
		$this->myAdvancedManipulationEngine = $advancedManipulationEngine;

		$this->from = $from;
		$this->to = $to;
	}

	public function getProducts(){

		$products = $this->myAdvancedManipulationEngine->retrieveData(
			'products',
			NULL,
			NULL,
			array('id' => '[' . $this->from . ',' . $this->to . ']')
			);

		$productsHashmap;
		$productsHashmapKey;
		$productsArray;
		
		$product = $products->children()->children();
		foreach ($product as $key => $singleProductAttributes) {
			foreach ($singleProductAttributes as $key => $value) {

				switch ($key) {
					case  'id':
					$productsHashmapKey = $value;
					case 'id_address_delivery':
						$productsArray['name'] = $value;
						break;
					default:
						break;
				}
			}
			$productsHashmap[(string)$productsHashmapKey] = $productsArray;
		}
		return $productsHashmap;
	}
}