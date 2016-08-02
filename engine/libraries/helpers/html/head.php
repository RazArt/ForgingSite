<?php
    namespace Helper\Html
    {
        class Head
        {
            public static function getMeta($httpEquil, $content)
            {
            
                $string = '<meta http-equiv="' . $httpEquil . '" content="' . $content . '" />';
                
                
            }
        }
    }
