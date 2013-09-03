<?php
// Including the autoloader and instanciating it
include_once('./tools/bisAutoloader.php');

$myMonkey = new MainMonkey();
$myMonkey->finalActionFormListener('syncCustomers');
$myMonkey->finalActionFormListener('syncOrders');
$myMonkey->finalActionFormListener('productsPrestashopToGestimum');
$myMonkey->finalActionFormListener('productsGestimumToPrestashop');
$myMonkey->render();

/*
$myEngine = new AdvancedManipulationEngine(Constants::getShopAddress(), Constants::getWebServiceKey());
$c = odbc_connect(Constants::getSQLServerConnectionString(),Constants::getDataBaseUsername(), Constants::getDataBasePassword());

$productsMonkey = new productsMonkey($c, $myEngine, 700, 710, 'site A');
$productsMonkey->synchronizeAll();*/