<?php 
// Including the autoloader and instanciating it
include_once('twig/lib/Twig/Autoloader.php');
    Twig_Autoloader::register();
     
    $loader = new Twig_Loader_Filesystem('templates'); // The directory which contains the template files
    $twig = new Twig_Environment($loader, array(
      'cache' => false
    ));
// Launching the rendering
echo $twig->render('index.twig', array(
        'moteur_name' => 'Twig',
        'tab' => array('nom' => 'Mayas')
    ));