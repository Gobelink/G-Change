<?php 
class MainMonkey{
	
	protected $loader;
	protected $twigInstance;
	protected $constantsInstance;

	function __construct(){
		
		Twig_Autoloader::register();
		// The directory which contains the template files
		$this->loader = new Twig_Loader_Filesystem('templates');

		$this->twigInstance = new Twig_Environment($this->loader, array(
    												'cache' => false
													));
		$this->constantsInstance = new Constants();
	}

	public function getAdvancedEngine($shopId){

		return new AdvancedManipulationEngine(
			$this->constantsInstance->getShopAddress($shopId), 
			$this->constantsInstance->getWebServiceKey($shopId)
		);
	}

	public function getDatabaseConnection(){
		
		return odbc_connect(
			$this->constantsInstance->getSQLServerConnectionString(),
			$this->constantsInstance->getDataBaseUsername(),
			$this->constantsInstance->getDataBasePassword()
		);
	}

	public function synchronizeCustomers($from, $to, $origin){
		
		$customersMonkey = new customersMonkey(
			$this->getDatabaseConnection(), 
			$this->getAdvancedEngine($origin),
			$from, 
			$to, 
			$origin
		);
		$customersMonkey->synchronizeAll();
	}

	public function synchronizeOrders($from, $to, $origin){
		
		$ordersMonkey = new ordersMonkey(
			$this->getDatabaseConnection(), 
			$this->getAdvancedEngine($origin),
			$from, 
			$to
		);
		$ordersMonkey->synchronizeAll();
	}
	
	public function synchronizeProducts($from, $to, $origin, $syncToPrestashop, $limit = 1, $gestimumProductId = ''){
		
		if(empty($limit)){
			$limit = 1;
		}
		
		$productsMonkey = new productsMonkey(
			$this->getDatabaseConnection(), 
			$this->getAdvancedEngine($origin),
			$from,
			$to,
			$origin
		);

		if($syncToPrestashop){
			$productsMonkey->synchronizeGestimumToPrestashop($limit, $gestimumProductId);
		}else{
			$productsMonkey->synchronizePrestashopToGestimum();
		}
	}

	public function finalActionFormListener($form){
		
		if(isset($_POST[$form])){
			switch ($form) {
				case 'syncCustomers':
					if(!empty($_POST['from']) && !empty($_POST['to']) && !empty($_POST['origin'])){
						if($_POST['from'] <= $_POST['to']){
							$this->synchronizeCustomers(
								(int) $_POST['from'],
								(int) $_POST['to'],
								(int) $_POST['origin']
							);
						}
					}
					break;
				case 'syncOrders':
					if(!empty($_POST['from']) && !empty($_POST['to']) && !empty($_POST['origin'])){
						if($_POST['from'] <= $_POST['to']){
							$this->synchronizeOrders(
								(int) $_POST['from'],
								(int) $_POST['to'],
								(int) $_POST['origin']
							);
						}
					}
					break;
				case 'productsPrestashopToGestimum':
					if(!empty($_POST['from']) && !empty($_POST['to']) && !empty($_POST['origin'])){
						if($_POST['from'] <= $_POST['to']){
							
							$this->synchronizeProducts(
								(int) $_POST['from'],
								(int) $_POST['to'],
								(int) $_POST['origin'],
								false
							);
						}
					}
					break;
				case 'productsGestimumToPrestashop':
					if(!empty($_POST['origin'])){
	
						$this->synchronizeProducts(
							1,
							1,
							(int) $_POST['origin'],
							true,
							$_POST['limit'],
							$_POST['art-code']
						);
					}
					break;
					case 'newOrderFromPrestashop':
					if(!empty($_POST['from']) && !empty($_POST['to']) && !empty($_POST['origin']) && !empty($_POST['key'])){
						if($_POST['from'] <= $_POST['to'] && $this->constantsInstance->isKnown($_POST['key'])){
							$this->synchronizeOrders(
								(int) $_POST['from'],
								(int) $_POST['to'],
								(int) $_POST['origin']
							);
						}
					}
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

/*include('./tools/bisAutoloader.php');
// Replace path and key with your own.
$myEngine = new AdvancedManipulationEngine(Constants::getShopAddress(), Constants::getWebServiceKey());

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