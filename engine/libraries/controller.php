<?php
    namespace Engine
    {
        class Controller
        {
            static function accessCheck($accessLevel = 1) 
            {
                if ($access_level == 0
                    && \App\Account::getLoginStatus()
                ) {
                    exit();
                } elseif ($access_level == 1
                    && !\App\Account::get_login_status()
                ) {
                    exit();
                }
            }
        }
    }
