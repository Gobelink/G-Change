<?php
class CustomersMonkey{
	private $myAdvancedManipulationEngine;

	public function getCustomerAddress($customerId){
	
		this->myAdvancedManipulationEngine->retrieveData('customers'
														, $customerId
														, array('id_customer' 
																	=> $customerId
																)
														);
	
	}

	public __construct($advancedManipulationEngine){
	
		$this->myAdvancedManipulationEngine = $advancedManipulationEngine;
	}
}