<?php
    if (version_compare(phpversion(), '5.3.0', '<') == true) {
        die ('Error: PHP 5.3+ Only');
    }
    
    if (!extension_loaded('json')) {
        die ('Error: \'json\' not loaded');
    }
    
    if (!extension_loaded('PDO')) {
        die ('Error: \'PDO\' not loaded');
    }
    
    if (!extension_loaded('pdo_mysql')) {
        die ('Error: \'pdo_mysql\' not loaded');
    }
    
    define('DEBUG_MODE', true);
    //Test
    if (DEBUG_MODE) {
        error_reporting(E_ALL);
    } else {
        ini_set ('display_errors', true);
        ini_set ('html_errors', false);
		ini_set ('error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE);
    }
    
    define('D_S', DIRECTORY_SEPARATOR);
    define('SITE_PATH', realpath(dirname(__FILE__) . D_S) . D_S . '..' . D_S);
    define('ENGINE_PATH', SITE_PATH . 'engine' . D_S);
    define('APPLICATION_PATH', SITE_PATH . 'application' . D_S);
    define('CONFIG_PATH', SITE_PATH . 'config' . D_S);
    define('LIBRARIES_ENGINE_PATH', ENGINE_PATH . 'libraries' . D_S);
    define('LIBRARIES_APP_PATH', APPLICATION_PATH . 'libraries' . D_S);
    define('HELPERS_PATH', ENGINE_PATH . 'libraries' . D_S . 'helpers' . D_S);
    define('CONTROLLERS_PATH', APPLICATION_PATH . 'controllers' . D_S);
    define('MODELS_PATH', APPLICATION_PATH . 'models' . D_S);
    define('CACHE_PATH', SITE_PATH . 'cache' . D_S);
    define('LOGS_PATH', SITE_PATH . 'logs' . D_S);
    define('TEMPLATE_PATH', APPLICATION_PATH . 'templates' . D_S);
    
    if (function_exists('set_time_limit') == TRUE 
        && ini_get('safe_mode') == 0
    ) {
        set_time_limit(0);
    }
    
    session_start();
    
    $loaderPath = LIBRARIES_ENGINE_PATH . 'loader.php';
    
    if (!file_exists($loaderPath) 
        || !is_readable($loaderPath)
    ) {
        die ('Error: \'Loader class\' not loaded');
    } else {
        require $loaderPath;
        unset($loaderPath);
    }
    
    new Engine\Loader;
    new Engine\Config;
    if (!Engine\Loader::loadFile(APPLICATION_PATH . 'bootstrap.php')) {
        die ('Error: \'Bootstrap\' not loaded');
    }
