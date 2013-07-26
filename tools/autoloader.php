<?php
class autoloader {

    public static $loader;

    public static function init(){
        if (self::$loader == NULL)
            self::$loader = new self();

        return self::$loader;
    }

    public function __construct(){
	
        spl_autoload_register(array($this,'monkeysInterfaces'));
        spl_autoload_register(array($this,'monkeys'));
        spl_autoload_register(array($this,'tools'));
        spl_autoload_register(array($this,'toolsIntrefaces'));
        spl_autoload_register(array($this,'library'));

    }

    public function monkeys($class){
        set_include_path('./monkeys/');
        spl_autoload_extensions('.php');
        spl_autoload($class);
    }

    public function monkeysInterfaces($class){
        set_include_path('./monkeys/interfaces/');
        spl_autoload_extensions('.php');
        spl_autoload($class,'.php');
    }

    public function tools($class){
        set_include_path('./tools/');
        spl_autoload_extensions('.php');
        spl_autoload($class);
    }

    public function toolsIntrefaces($class){
        set_include_path('./tools/interfaces');
        spl_autoload_extensions('.php');
        spl_autoload($class);
    }

    public function library($class){
        set_include_path('./tools/lib/');
        spl_autoload_extensions('.php');
        spl_autoload($class);
    }
}

autoloader::init();
