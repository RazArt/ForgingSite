<?php
    namespace Controller
    {
        class Online
        {
            function __construct()
            {
                $view = \Engine\View::instance();
                $view -> setDir('controllers' . D_S . 'online');
                $view -> route = \Engine\Request::get('online', \Engine\Request::TYPE_STRING);
            }
            
            public function action_index($controllerArguments) 
            {
                $model = new \Model\Online();
                $view = \Engine\View::instance();
                $view -> onlineList = $model -> getOnlineList();
                $view -> sendResponse('index');
                
                return;
            }
        }
    }