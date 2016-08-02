<?php
    namespace Engine
    {
        class Registry extends RegistryAbstract
        {
            private static $_instance;
            
            static public function instance()
            {
                return is_null(self::$_instance)
                    ? self::$_instance = new static()
                    : self::$_instance;
            }
        }
    }