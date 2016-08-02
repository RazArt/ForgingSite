<?php
    namespace Engine
    {
        class Config extends ConfigAbstract
        {
            private static $_instance;
            
            static public function instance()
            {
                return is_null(self::$_instance)
                    ? self::$_instance = new static()
                    : self::$_instance;
            }
            
            public function __construct()
            {
                if (!$this -> load('engine')) {
                    die ('Engine config not loaded');
                }
            }
        }
    }