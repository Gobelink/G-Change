<?php
// Including the autoloader and instanciating it
include_once('./tools/bisAutoloader.php');

$myMonkey = new MainMonkey();
$myMonkey->finalActionFormListener('syncCustomers');
$myMonkey->render();