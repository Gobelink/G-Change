<?php

class OrdersMonkey{
	
	protected $myAdvancedManipulationEngine;

	protected $from;
	protected $to;

	function __construct($advancedManipulationEngine, $from, $to){
		$this->myAdvancedManipulationEngine = $advancedManipulationEngine;

		$this->from = $from;
		$this->to = $to;
	}

	public function getOrders(){

		$orders = $this->myAdvancedManipulationEngine->retrieveData(
			'orders',
			NULL,
			NULL,
			array('id' => '[' . $this->from . ',' . $this->to . ']')
			);

		$ordersHashmap;
		$ordersHashmapKey;
		$ordersArray;
		
		$order = $orders->children()->children();
		foreach ($order as $key => $singleOrderAttributes) {
			foreach ($singleOrderAttributes as $key => $value) {

				switch ($key) {
					case  'id':
					$ordersHashmapKey = $value;
					case 'id_address_delivery':
						$ordersArray['id_address_delivery'] = $value;
						break;
					case 'id_address_invoice':
						$ordersArray['id_address_invoice'] = $value;
						break;
					case 'id_cart':
						$ordersArray['id_cart'] = $value;
						break;
					case 'id_currency':
						$ordersArray['id_currency'] = $value;
						break;
					case 'id_lang':
						$ordersArray['id_currency'] = $value;
						break;
					default:
						break;
				}
			}
			$ordersHashmap[(string)$ordersHashmapKey] = $ordersArray;
		}
		return $ordersHashmap;
	}
}