<?php
    namespace Engine
    {
        class Request
        {
            const TYPE_ARRAY = 0;
            const TYPE_BOOLEAN = 1;
            const TYPE_INTEGER = 2;
            const TYPE_FLOAT = 3;
            const TYPE_STRING = 4;
            const TYPE_OBJECT = 5;
            
            static private $_request = [];
            
            static private $_ajaxMode = false;
            
            public function __construct() 
            {
                self::$_request = $_POST;
                
                self::$_request['route'] = isset($_GET['route']) 
                                                ? trim($_GET['route'], '/\\') 
                                                : '';
                
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
                    && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
                    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
                ) {
                    self::$_ajaxMode = true;
                }
            }
     
            static public function get($key, $type = null) 
            {
                if (!isset(self::$_request[$key])) {
                    return null;
                }
                
                switch ($type) {
                    case 0: 
                        return (array)self::$_request[$key];
                    case 1:
                        return (boolean)self::$_request[$key];
                    case 2:
                        return (int)self::$_request[$key];
                    case 3:
                        return (float)str_replace(',', '.', self::$_request[$key]);
                    case 4:
                        return (string)self::$_request[$key];
                    case 5:
                        return (object)self::$_request[$key];
                    default:
                        return self::$_request[$key];
                }
            }
        
            static public function isAjax()
            {
                if (!self::$_ajaxMode) {
                    return false;
                }
                
                return true;
            }
        }
    }