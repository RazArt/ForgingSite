<?php
    namespace Engine
    {
        abstract class RegistryAbstract 
        {
            private $_registry = [];
            
            public function __set($key, $value)
            {
                if (isset($this -> _registry[$key])) {
                    unset($this -> _registry[$key]);
                }
                
                $this -> _registry[$key] = $value;
            }
            
            public function &__get($key)
            {
                return $this -> _registry[$key];
            }
            
            public function __isset($key) 
            {
                return isset($this -> _registry[$key]);
            }
            
            public function __unset($key) 
            {
                unset($this -> _registry[$key]);
            }
        }
    }