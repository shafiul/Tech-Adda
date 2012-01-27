<?php

/**
 * App Engine
 *
 * Initialize the environment, load the configuration, create
 * database connection, prepare utility classes and provide
 * factory to entity repositories.
 *
 */
class App {

    /**
     * @var Config
     */
    public static $config;

    /**
     * @var Sparrow
     */
    public static $db;
    
    /**
     * @var kLogger
     */
    public static $logInfo;
    public static $logDebug;
    public static $logError;
    

    public static function init($configFile) {
        self::setTimeZone();
        self::setErrorReporting();
        self::initSession();         
        self::initAutoload();        
        self::initLogger();
        self::loadConfiguration($configFile);                
        self::loadDatabaseConnection();
        self::loadUtility();
        
    }

    private static function setTimeZone() {
        date_default_timezone_set('Asia/Dacca');
    }

    private static function setErrorReporting() {
        ini_set("display_errors", "on");
        error_reporting(E_ALL ^ E_NOTICE);
    }

    private static function initSession() {
        session_start();
    }
        
    private static function initAutoload() {
        spl_autoload_register(function($className) {

                    $paths = explode(PATH_SEPARATOR, get_include_path());

                    foreach ($paths as $path) {
                        $file = $path . DIRECTORY_SEPARATOR . $className . '.php';
                        if (file_exists($file)) {
                            include_once $file;
                            return;
                        }
                    }
                });
    }
    private static function initLogger(){
        self::$logInfo = new KLogger ( APPPATH."/log/info.txt" , KLogger::INFO );
        self::$logError = new KLogger (APPPATH."/log/error.txt" , KLogger::ERROR );
        self::$logDebug = new KLogger (APPPATH."/log/debug.txt" , KLogger::DEBUG );
    }
    private static function loadConfiguration($configFile) {
        self::$config = new Config($configFile);
    }

    private static function loadDatabaseConnection() {
        $dsn = "mysqli://" . self::$config->get('db.user')
                . ":" . self::$config->get('db.pass')
                . "@" . self::$config->get('db.host')
                . ":" . self::$config->get('db.port')
                . "/" . self::$config->get('db.name');

        self::$db = new Sparrow($dsn);
    }

    private function loadUtility() {
        ViewHelper::setConfig(self::$config);
    }

    public static function run() {
        $page = !empty($_GET['page']) ? $_GET['page'] : 'home';

        if (!file_exists(APPPATH . '/controllers/' . $page . '.php')) {
            self::error('Controller Not Found');
        }

        include_once APPPATH . '/controllers/' . $page . '.php';
        // Create controller instance
        $controllerClass = ucfirst($page);
        $controller = new $controllerClass();
        $controller->index();
    }

    public static function getRepository($entity) {
        $repository = ucfirst($entity) . 'Repository';
        return new $repository(self::$db);
    }

    public static function error($message) {
        App::logMsg('App Framework Fatal: ' . $message, 'Error');
        header('Location: ' . ViewHelper::url('?page=error', true));
        exit();
//        trigger_error($message, E_USER_ERROR);
    }
    public static function logMsg($message,$type='Error'){        
        $type = ucfirst($type);
        $varName = "log".$type;                
        App::$$varName->$varName($message);        
    }
    
    

}