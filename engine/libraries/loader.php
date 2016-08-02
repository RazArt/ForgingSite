<?php
    namespace Engine
    {
        class Loader
        {
            public function __construct()
            {
                spl_autoload_register([$this, 'loadClass']);
            }
            
            static public function loadClass($className)
            {
                $classPath = str_replace('\\', D_S, trim($className, '\\') . '.php');
                $classDir = substr($classPath, 0, strpos($classPath, D_S));
                $classPath = strtolower(substr($classPath, strpos($classPath, D_S) + 1));
                
                if ($classDir == 'Engine') {
                    $classPath = LIBRARIES_ENGINE_PATH . $classPath;
                } elseif ($classDir == 'App') {
                    $classPath = LIBRARIES_APP_PATH . $classPath;
                } elseif ($classDir == 'Controller') {
                    $classPath = CONTROLLERS_PATH . $classPath;
                } elseif ($classDir == 'Model') {
                    $classPath = MODELS_PATH . $classPath;
                } else {
                    die ('Class \'' . $className . '\' not loaded');
                }
                
                 if (!file_exists($classPath) 
                    || !is_readable($classPath) 
                ) {
                    die ('Class \'' . $className . '\' not loaded');
                } else {
                    require_once ($classPath);
                }
            }
            
            static public function loadFile($filePath)
            {
                if (!file_exists($filePath) 
                    || !is_readable($filePath) 
                ) {
                    return null;
                } else {
                    return require ($filePath);
                }
            }
        }
    }