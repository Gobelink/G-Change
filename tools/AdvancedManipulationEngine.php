<?php
require_once('lib/PSWebServiceLibrary.php'); // Must be in the same directory.
require_once('interfaces/manipulationEngine.php');
class AdvancedManipulationEngine implements manipulationEngine {
	
	protected $prestashopWebService; // PrestaShopWebService object.

	protected $shopURL; // URL to the shop which we want to work with.
	
	protected $key; // Key to the web service.
	
	function __construct($shopURL, $key){
		
		$this->shopURL = $shopURL;
		$this->key = $key;

		try{
			$this->prestashopWebService = new PrestaShopWebservice($shopURL, $key, false);
		}catch(PrestaShopWebserviceException $e){
			echo 'Error while building object: <br/>' . $e->getMessage();
		}
	}
	
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

	public function retrieveData($entityResource){
		
		try{
			// We will retrieve every node for every customer thanks to "display = full" option.
			$opt = array(
    		'resource' => $entityResource,
    		'display' => 'full'
    		);

			$xml = $this->prestashopWebService->get($opt);
			$resources = $this->getGrandChildren($xml);
			
			foreach ($resources as $parentResource){
				$resource = $parentResource->children();
				return $resource;
			}
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