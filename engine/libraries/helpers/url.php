<?php
    namespace Helper
    {
        class Url
        {
            public static function getUrl($controller = false, $action = false, $arguments = [])
            {
                $url = \Engine\Config::instance() -> path;
                
                if ($controller) {
                    $url .= $controller . '/';
                }
                
                if ($action) {
                    $url .= $action . '/';
                }
                
                if (sizeof($arguments)) {
                    foreach ($arguments as $key => $value) {
                        $url .= $key . '=' . $value . '/';
                    }
                }
                
                return $url;
            }
        }
    }