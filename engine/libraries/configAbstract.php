<?php
    namespace Engine
    {
        abstract class ConfigAbstract 
        {
            private $_config = [];
            
            public function load($fileName)
            {
                $filePath = CONFIG_PATH . $fileName . '.json';
                
                if (!$config = Json::load($filePath)) {
                    return false;
                } else {
                    return $this -> _config[$fileName] = $config;
                }
            }
            
            public function __set($key, $value)
            {
                if (isset($this -> _config[$key])) {
                    unset($this -> _config[$key]);
                }
                
                $this -> _config[$key] = $value;
            }
            
            public function &__get($key)
            {
                return $this -> _config[$key];
            }
            
            public function __isset($key) 
            {
                return isset($this -> _config[$key]);
            }
            
            public function __unset($key) 
            {
                unset($this -> _config[$key]);
            }
        }
    }