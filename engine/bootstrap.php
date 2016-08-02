<?php
    class Bootstrap
    {
        static public function run()
        {
            session_start();
            
            if (function_exists('set_time_limit') == TRUE 
                && ini_get('safe_mode') == 0
            ) {
                set_time_limit(0);
            }
            
            $loaderPath = LIBRARIES_ENGINE_PATH . 'loader.php';
            
            if (!file_exists($loaderPath) 
                || !is_readable($loaderPath)
            ) {
                die ('Loader class not loaded');
            } else {
                require $loaderPath;
                unset($loaderPath);
            }
            
            new \Engine\Loader;
            
            \Engine\Config::instance();
            
            date_default_timezone_set(\Engine\Config::instance() -> timezone);
            
            new \App\Application;
        }
    }