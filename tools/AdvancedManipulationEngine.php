<?php
require_once('lib/PSWebServiceLibrary.php'); // Must be in the same directory.
require_once('interfaces/manipulationEngine.php');
class AdvancedManipulationEngine implements manipulationEngine {
	
	protected $prestashopWebService; // PrestaShopWebService object.

	protected $shopURL; // URL to the shop which we want to work with.
	
	protected $key; // Key to the web service.
	
	public function createData($entity, $entityResource){

		try{
			$xml = $this->prestashopWebService->get(
													array(
															'url' => 
															$this->shopURL . '/api/' .
															$entityResource . 
															'?schema=blank'
														)
													);
			
			$resources = $this->getGrandChildren($xml);
			foreach ($resources as $nodeKey => $node){
				if(array_key_exists($nodeKey, $entity)) // PHP >= 4.0.7
					$resources->$nodeKey = $entity[$nodeKey];
			}

			$opt = array('resource' => $entityResource);
			$opt['postXml'] = $xml->asXML();
			$xml = $this->prestashopWebService->add($opt);
			echo "Successfully added! <br/>";
		}catch(PrestaShopWebserviceException $e){
			echo 'Error while creating ' . $entityResource . ' data: <br/>' . $e->getMessage();
		}
	}

	public function retrieveData($entityResource, $entityId, $displayAttributes, $entityFilter){
		
		try{
			$opt = array(
    		'resource' => $entityResource,
    		);
    		
    		if(isset($entityId)){
				$opt['id'] = $entityId;
    		}

    		$doNotDisplayFull = false; // We want to display full data.

    		if(isset($entityFilter)){

    			foreach ($entityFilter as $filterName => $filterValue) {
    				$opt['filter[' . $filterName . ']']  = (string) $filterValue;
    			}
    		}

    		// If not given an array with the rows to display.
    		if($displayAttributes == NULL || sizeof($displayAttributes) == 0){
    			$opt['display'] = 'full';
    		}else{
    			$displayValue = '';
    			foreach ($displayAttributes as $key => $value) {
    				$displayValue = $displayValue . ',' . $value;
    			}
    			;
    			$displayValue = trim($displayValue, ',');
    			$opt['display'] = '[' . $displayValue . ']';
    		}
			$xml = $this->prestashopWebService->get($opt);
			return $xml;
			
			/*$resources = $this->getGrandChildren($xml);
			
			foreach ($resources as $parentResource){
				$resource = $parentResource->children();
				echo '<table border="5">';
				
				foreach ($resource as $key => $value){
					echo '<tr>';
					echo '<th>' . $key . '</th><td>' . $value .'</td>';
					echo '</tr>';
				}
				echo '</table>';
			}*/
		}catch(PrestaShopWebserviceException $e){
			echo 'Error while retrieving ' . $entityResource . ' data: <br/>' . $e->getMessage();
		}
	}

	public function updateData($entity, $entityResource){
		try{
			if(!array_key_exists('id', $entity)){ // PHP >= 4.0.7
				echo 'You must provide the ID of this entity you want to update.  <br/>';
				return;
			}
			$opt = array('resource' => $entityResource);
			$opt['id'] = $entity['id'];
			$xml = $this->prestashopWebService->get($opt);
			$resources = $this->getGrandChildren($xml);
				
			foreach ($resources as $nodeKey => $node){
				if(array_key_exists($nodeKey, $entity)){ // PHP >= 4.0.7
					$resources->$nodeKey = $entity[$nodeKey];
				}
			}
			$opt = array('resource' => $entityResource);
			$opt['putXml'] = $xml->asXML();
			$opt['id'] = $entity['id'];
			$xml = $this->prestashopWebService->edit($opt);
			echo "Successfully updated!  <br/>";
		}catch(PrestaShopWebserviceException $e){
			echo 'Error while updating ' . $entityResource . ' data: <br/>' . $e->getMessage();
		}
	}

	public function deleteData($deletingEntityId, $entityResource){
		try{
			$this->prestashopWebService->delete(array('resource' => $entityResource, 'id' => intval($deletingEntityId)));
			echo 'Successfully deleted! <br/>';
		}catch(PrestaShopWebserviceException $e){
			echo 'Error while deleting ' . $entityResource . ' data: <br/>' . $e->getMessage();
		}
	}

	public function getGrandChildren($xml){
		return $xml->children()->children();
	}

	function __construct($shopURL, $key){
		
		$this->shopURL = $shopURL;
		$this->key = $key;

		try{
			$this->prestashopWebService = new PrestaShopWebservice($shopURL, $key, false);
		}catch(PrestaShopWebserviceException $e){
				echo 'Error while building object: <br/>' . $e->getMessage();
		}
	}

}

/*
EXAMPLE:
// Replace path and key with your own.
$myEngine = new PrestashopManipulationEngine('http://myShop.com', 'YourKey');
$myEngine->createData(
						array(
							  'lastname' => 'Laglak',
						  	  'firstname' => 'Frigud',
							  'email' => 'Frigud@example.com',
							  'passwd' => 'hishackablepassword',
							  'note' => 'He is happy'
							  ),
						'customers'
					 );

// The ID is important and is not updatable, provide it so that the corresponding entity will be updated.
/*$myEngine->updateData(
						array(
							  'id' => '1',
							  'lastname' => 'Vlodvek',
						  	  'firstname' => 'Heinrich',
							  'email' => 'Heinrich@example.com',
							  'passwd' => 'hishackablepassword',
							  'note' => 'He is not'
							  ),
						'customers'
					 );
//$myEngine->deleteData('1','customers');
// For the moment, displays customers with one table per customer.
$myEngine->retrieveData();
*/