<?php
// On linux, this autoloader works, while "autoloader.php" does not
// the order of requires is important when there are dependencies!
// the order should be as follows Constants, libraries, interfaces, classes
class bisAutoloader{
	public static function init(){
		$classes = array(
				'MainMonkey',
				'Constants',
				'Utility',
				'PrestaShopWebservice',
				'manipulationEngine',
				'monkey',
				'AdvancedManipulationEngine',
				'CustomersMonkey',
				'OrdersMonkey',
				'ProductsMonkey',
				'Autoloader'
			);
	array_walk($classes, 'bisAutoLoader::performRequire');		
}

public static function performRequire($className){
	if(file_exists($className . '.php')){
		require_once($className . '.php');
		return;
	}
	if(file_exists('tools/' . $className . '.php')){
		require_once('tools/' . $className . '.php');
		return;
	}
	if(file_exists('tools/interfaces/' . $className . '.php')){
		require_once('tools/interfaces/' . $className . '.php');
		return;
	}
	if(file_exists('tools/lib/' . $className . '.php')){
		require_once('tools/lib/' . $className . '.php');
		return;
	}
	if(file_exists('monkeys/' . $className . '.php')){
		require_once('monkeys/' . $className . '.php');
		return;
	}
	if(file_exists('monkeys/interfaces/' . $className . '.php')){
		require_once('monkeys/interfaces/' . $className . '.php');
		return;
	}
	if(file_exists('twig/lib/Twig/' . $className . '.php')){
		require_once('twig/lib/Twig/' . $className . '.php');
		return;
	}
}

}
bisAutoloader::init();