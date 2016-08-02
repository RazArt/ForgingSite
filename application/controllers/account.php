<?php
    namespace Controller
    {
        class Account
        {
            function __construct()
            {
                $view = \Engine\View::instance();
                $view -> setDir('controllers' . D_S . 'account');
                $view -> route = \Engine\Request::get('route', \Engine\Request::TYPE_STRING);
            }
                    
            public function action_index($controllerArguments) 
            {
                $view = \Engine\View::instance();
                $view -> token = \Engine\Security::generateToken();
                $view -> sendResponse('index');
            }
                    
            public function action_check($controllerArguments) 
            {
                $view = \Engine\View::instance();
                
                if (!\Engine\Security::checkToken(\Engine\Request::get('token', \Engine\Request::TYPE_STRING))) {
                    header('Location: ' . \Engine\Url::getUrl('login'));
                }
                
                $userLogin = \Engine\Request::get('login', \Engine\Request::TYPE_STRING);
                $userPassword = \Engine\Request::get('password', \Engine\Request::TYPE_STRING);
                
                if (!isset($userLogin) 
                    || !isset($userPassword) 
                    || !preg_match('/^[a-zA-Z0-9_.]{3,12}$/', $userLogin) 
                    || !preg_match('/^[a-zA-Z0-9_.]{3,12}$/', $userPassword)
                ) {
                    $view -> sendResponse('loginIncorrectUsernamePassword', null, \Engine\View::RT_ERROR);
                    return;
                }
                
                $model = new \Model\Account();
                
                $accountInfo = $model -> checkUser($userLogin, sha1(strtoupper($userLogin) . ':' . strtoupper($userPassword)));
                
                if (!$accountInfo['id'] 
                    || $accountInfo['id'] < 1
                ) {
                    $view -> sendResponse('loginIncorrectUsernamePassword', null, \Engine\View::RT_ERROR);
                    
                    return;
                }
                
                $_SESSION['user'] = [];
                $_SESSION['user']['id'] = (int)$accountInfo['id'];
                $_SESSION['user']['name'] = (string)$accountInfo['username'];
                $_SESSION['user']['email'] = (string)$accountInfo['email'];
                $_SESSION['user']['ip'] = isset($_SERVER['REMOTE_ADDR']) 
                    ? $_SERVER['REMOTE_ADDR'] : '';
                $_SESSION['user']['userAgent'] = isset($_SERVER['HTTP_USER_AGENT']) 
                    ? $_SERVER['HTTP_USER_AGENT'] : '';
                
                $view -> sendResponse(null, null, \Engine\View::RT_DATA, ['check' => true]);
                        
                return;
            }
                    
            public function action_logout($controllerArguments) 
            {
                $view = \Engine\View::instance();
                
                if (isset($_SESSION['user'])) {
                    unset($_SESSION['user']);
                }
                
                $view -> sendResponse(null, null, \Engine\View::RT_DATA, ['logout' => true]);
                
                return;
            }
            
            public function action_setrealm($controllerArguments) 
            {
                $view = \Engine\View::instance();
               
                if (isset(\Engine\Config::instance() -> realms[(int)$controllerArguments['realm']])) {
                    \Engine\Registry::instance() -> selectedRealmId = (int)$controllerArguments['realm'];
                    
                    $view -> sendResponse(null, null, \Engine\View::RT_DATA, ['setrealm' => true]);
                }
                
                return;
            }
            
            public function action_confirmemail ($controllerArguments)
            {
                if (!\App\Account::checkAccessLevel(\App\Account::ACCESS_LEVEL_USER)) {
                    $view -> sendResponse('accessError', null, \Engine\View::RT_ERROR);
                        
                    return;
                }
                
                $model = new \Model\Account();
                $view = \Engine\View::instance();
                
                if (!$model -> confirmedMail()) {
                    if (!isset($controllerArguments['code'])) {
                    
                    } elseif (!$email = \Engine\Request::get('email', \Engine\Request::TYPE_STRING)) {
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $view -> sendResponse('emailValidationError', null, \Engine\View::RT_ERROR);
                            
                            return;
                        }
                    }
                }
                
                $view -> sendResponse('emailAlreadyCheked', null, \Engine\View::RT_ERROR);
                        
                return;
            }
        }
    }