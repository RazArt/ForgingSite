<?php
    namespace App
    {
        class Config extends \Engine\ConfigAbstract
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
                if (!$this -> load('app')) {
                    die ('App config not loaded');
                }
            }
        }
    }