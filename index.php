<?php
// Including the autoloader and instanciating it
include_once('twig/lib/Twig/Autoloader.php');
include_once('./tools/bisAutoloader.php');
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

	public function finalActionFormListener($form){
		
		if(isset($_POST[$form])){
			switch ($form) {
				case 'syncCustomers':
					if(isset($_POST['from'], $_POST['to'], $_POST['origin'])){
						$this->synchronizeCustomers((int) $_POST['from'], (int) $_POST['to'], htmlspecialchars($_POST['origin']));
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
$myMonkey = new MainMonkey();
$myMonkey->finalActionFormListener('syncCustomers');
$myMonkey->render();