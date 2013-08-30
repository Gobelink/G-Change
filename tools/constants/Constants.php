<?php
 
class Constants{
        
        const INI_FILE = 'MexicanMonkey.ini';

        protected $prestashopUrls;
        protected $webServiceKeys;
        protected $sqlServerConnectionString;
        protected $sqlServerUsername;
        protected $sqlServerPassword;
        protected $plateformNames;

        public static function getShopNameFromId($shopId){
                if ($shopId == 1) {
                        return 'catalogue';
                }elseif ($shopId == 2) {
                        return 'boutique';
                }
        }

        public function getShopAddress($shopId){

                foreach ($this->prestashopUrls as $shopAsKey => $url) {
                        if (self::getShopNameFromId($shopId) == $shopAsKey) {
                                return $url;
                        }
                }
        }
 
        public function getWebServiceKey($shopId){
                foreach ($this->webServiceKeys as $shopAsKey => $webServiceKey) {
                        if (self::getShopNameFromId($shopId) == $shopAsKey) {
                                return $webServiceKey;
                        }               
                }
        }

        public function getSQLServerConnectionString(){
                
                return $this->sqlServerConnectionString;
        }
 
        public function getDataBaseUsername(){
          
                return $this->sqlServerUsername;
        }
 
        public function getDataBasePassword(){
        
                return $this->sqlServerPassword;
        }

        public function processIniFileContent($content){
                
                $shop = '';
                $shopUrl = '';
                $shopKey = '';
                $sqlServer = '';
                $dbConnectionString = '';
                $dbUsername = '';
                $dbPswd = '';

                $params = explode('+', $content);

                foreach ($params as $key => $value) {
                        
                        $singleParam = explode(';', $value);

                        foreach ($singleParam as $key => $value) {
                                $value = trim($value);
                                switch ($value) {
                                        case 'boutique':
                                        case 'catalogue':
                                                $shop = $value;
                                                break;
                                        case 'sqlserver':
                                                $sqlServer = $value;
                                                break;
                                        default:
                                                if(preg_match('/=/', $value)){
                                                        $entityAttribute = explode('=', $value);

                                                        $entityAttribute[0] = trim($entityAttribute[0]);
                                                        $entityAttribute[1] = trim($entityAttribute[1]);
                                                        switch($entityAttribute[0]){
                                                                case 'url':
                                                                        $shopUrl = $entityAttribute[1];
                                                                break;
                                                                case 'key':
                                                                        $shopKey = $entityAttribute[1];
                                                                break;
                                                                case 'costr':
                                                                        $dbConnectionString = $entityAttribute[1];
                                                                break;
                                                                case 'usrnm':
                                                                        $dbUsername = $entityAttribute[1];
                                                                break;
                                                                case 'pswd':
                                                                        $dbPswd = $entityAttribute[1];
                                                                break;
                                                                case 'name':
                                                                        $platformName = $entityAttribute[1];
                                                                break;
                                                                default:
                                                                break;
                                                        }
                                                }
                                        break;
                                }
                        }

                        if($shop != ''){ 
                                if ($shopUrl != '' && $shopKey != '') {
                                        $this->prestashopUrls[$shop] = $shopUrl;  
                                        $this->webServiceKeys[$shop] = $shopKey;                                            
                                }                               
                                
                                if ($platformName != '') {
                                        if(in_array($platformName, array_values($this->plateformNames))){
                                                throw new DuplicatePlatformNameException(
                                                        "You must provide a unique name per Platform", 
                                                        1
                                                );
                                        }
                                        $this->plateformNames[$shop] = $platformName;
                                }
                                
                                // Reinitializing the variables
                                $shop = '';
                                $shopUrl = '';
                                $shopKey = '';
                                $platformName = '';               
                        }elseif ($sqlServer != '' && $dbConnectionString != '' && $dbUsername != '' && $dbPswd != '') {
                                $this->sqlServerConnectionString = $dbConnectionString;
                                $this->sqlServerUsername = $dbUsername;
                                $this->sqlServerPassword = $dbPswd;
                                
                                // Reinitializing the variables
                                $sqlServer = '';
                                $dbConnectionString = '';
                                $dbUsername = '';
                                $dbPswd = '';
                        }
                }
        }
        public function getPlatformNames(){
                return $this->plateformNames;
        }

        public function isKnown($key)
        {
                return in_array($key, array_values($this->getPlatformNames()));
        }
        function __construct(){
                $this->plateformNames = array();
                if(file_exists(self::INI_FILE)){
                        $handle = fopen(self::INI_FILE, 'r');
                        if(filesize(self::INI_FILE) > 0){
                                $content = fread($handle, filesize(self::INI_FILE));
                                $this->processIniFileContent($content);
                        }else{
                                throw new EmptyIniFileException("ini file empty", 1);
                                
                        }
                }else{
                        throw new IniFileNotFoundException("no MexicanMonkey ini file", 2);
                }
        }
}

class EmptyIniFileException extends Exception {}
class IniFileNotFoundException extends Exception {}
class DuplicatePlatformNameException extends Exception {}
/*
boutique
name:
url:
key:
/
catalogue
name:
url:
key:
/
sqlserver
costr:
usrnm:
pswd:
/
Edgard Morin
*/