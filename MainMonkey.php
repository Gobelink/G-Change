<?php 
class MainMonkey{
	
	protected $loader;
	protected $twigInstance;

	function __construct(){
		
		Twig_Autoloader::register();
		// The directory which contains the template files
		$this->loader = new Twig_Loader_Filesystem('templates');

		$this->twigInstance = new Twig_Environment($this->loader, array(
    												'cache' => false
													));
	}

	public function getEngineAdvancedEngine(){

		return new AdvancedManipulationEngine(
			Constants::getShopAddress(), 
			Constants::getWebServiceKey()
		);
	}

	public function getDatabaseConnection(){
	
		return odbc_connect(
			Constants::getSQLServerConnectionString(),
			Constants::getDataBaseUsername(),
			Constants::getDataBasePassword()
		);
	}

	public function synchronizeCustomers($from, $to, $origin){
		
		$customersMonkey = new customersMonkey(
			$this->getDatabaseConnection(), 
			$this->getEngineAdvancedEngine(),
			$from, 
			$to, 
			$origin
		);
		$customersMonkey->synchronizeAll();
	}

	public function synchronizeOrders($from, $to){
		$ordersMonkey = new ordersMonkey(
			$this->getDatabaseConnection(), 
			$this->getEngineAdvancedEngine(),
			$from, 
			$to
		);
		$ordersMonkey->synchronizeAll();
	}
	
	public function synchronizeProducts($from, $to, $origin){
		$productsMonkey = new productsMonkey(
			$this->getDatabaseConnection(), 
			$this->getEngineAdvancedEngine(),
			$from,
			$to,
			$origin
		);
		$productsMonkey->synchronizeAll();
	}
	public function finalActionFormListener($form){
		
		if(isset($_POST[$form])){
			switch ($form) {
				case 'syncCustomers':
					if(!empty($_POST['from']) && !empty($_POST['to']) && !empty($_POST['origin'])){
						$this->synchronizeCustomers(
							(int) $_POST['from'],
							(int) $_POST['to'],
							htmlspecialchars($_POST['origin'])
						);
					}
					break;
				case 'syncOrders':
					if(!empty($_POST['from']) && !empty($_POST['to'])){
						$this->synchronizeOrders(
							(int) $_POST['from'],
							(int) $_POST['to']
						);
					}
					break;
				case 'syncProducts':
					if(!empty($_POST['from']) && !empty($_POST['to']) && !empty($_POST['origin'])){
						$this->synchronizeProducts(
							(int) $_POST['from'],
							(int) $_POST['to'],
							htmlspecialchars($_POST['origin'])
						);
					}
					break;
				default:
					break;
			}
		}
	}

	public function getTemplateVariables(){
		
		$templateVariables = array();
		if(isset($_GET['module'])){
			switch ($_GET['module']) {
				case 'customers':
					$templateVariables['module'] = 'customers';
					break;
				case 'products':
					$templateVariables['module'] = 'products';
					break;
				case 'orders':
					$templateVariables['module'] = 'orders';
					break;
				default:
					break;
			}
		}
		return $templateVariables;
	}

	public function render(){
		// Launching the rendering
		echo $this->twigInstance->render('index.twig', 
											$this->getTemplateVariables()
										);
	}
}
/*
include('./tools/bisAutoloader.php');
// Replace path and key with your own.
$myEngine = new AdvancedManipulationEngine(Constants::getShopAddress(), Constants::getWebServiceKey());
/*
$myEngine->createData(
						array(
							  'lastname' => 'tamerlank',
						  	  'firstname' => 'titi',
							  'email' => 'mayus@name.com',
							  'passwd' => 'mayus',
							  'note' => 'Homme à Lynda'
							  ),
						'customers'
					 );

*/
// The ID is important and is not updatable, provide it so that the corresponding entity will be updated.
/*$myEngine->updateData(
						array(
							  'id' => '5',
							  'lastname' => 'Vlodvek'
							  ),
						'customers'
					 );
//$myEngine->deleteData('7','customers');
$c = odbc_connect(Constants::getSQLServerConnectionString(),Constants::getDataBaseUsername(), Constants::getDataBasePassword());

$myCustomerssMonkey = new CustomersMonkey($c, $myEngine, 100, 700,'');

$myCustomerssMonkey->synchronizeAll();
*/