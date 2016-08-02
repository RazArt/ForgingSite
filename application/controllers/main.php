<?php
    namespace Controller
    {
        class Main
        {
            function __construct()
            {
                $view = \Engine\View::instance();
                $view -> setDir('controllers' . D_S . 'main');
                $view -> route = \Engine\Request::get('route', \Engine\Request::TYPE_STRING);
            }
            
            public function action_index($controllerArguments) 
            {
                $view = \Engine\View::instance();
                $view -> sendResponse('index');
            }
        }
    }