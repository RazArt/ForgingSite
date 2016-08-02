<?php
    namespace Engine
    {
        class Security
        {
            public static function init() 
            {
                if (!isset($_SESSION['userCaptcha'])) {
                    $_SESSION['userCaptcha'] = [];
                }
                
                if (!isset($_SESSION['userToken'])) {
                    $_SESSION['userToken'] = [];
                }
            }
            
            public static function checkCaptcha($key, $code) 
            {
                if (DEBUG_MODE) {
                    return true;
                }
                
                $key = strtolower($key);
                $code = strtolower($code);
                
                if (isset($_SESSION['userCaptcha'][$key]) 
                    && ($_SESSION['userCaptcha'][$key]['code'] == $code)
                    && (time('now') - $_SESSION['userCaptcha'][$key]['tmstmp'] < 300)
                ) {
                    unset($_SESSION['userCaptcha'][$key]);
                    
                    return true;
                }
                
                unset($_SESSION['userCaptcha'][$key]);
                
                return false;
            }
    
            public static function generateCaptcha() 
            {
                foreach ($_SESSION['userCaptcha'] as $key => $value) {
                    if (time('now') - $value['tmstmp'] > 300) {
                        unset($_SESSION['userCaptcha'][$key]);
                    }
                }
                
                $captchaKey = strtolower(self::generatePassword(5));
                $captchaCode = strtolower(self::generatePassword(6));
                
                $_SESSION['userCaptcha'][$captchaKey]['code'] = $captchaCode;
                $_SESSION['userCaptcha'][$captchaKey]['tmstmp'] = time('now');
                
                return $captchaKey;
            }
    
            public static function generateToken() 
            {
                if (isset($_SESSION['userTokens'])) {
                    foreach ($_SESSION['userTokens'] as $value) {
                        if (time('now') - $value > 600) {
                            unset($_SESSION['userToken'][$value]);
                        }
                    }
                }
                
                $key = strtolower(self::generatePassword(10));
                
                $_SESSION['userTokens'][$key] = time('now');
                
                return $key;
            }
            
            public static function checkToken($key) 
            {
                if (DEBUG_MODE) {
                    return true;
                }
                
                $key = strtolower($key);
                
                if (isset($_SESSION['userTokens'][$key]) 
                    && (time('now') - $_SESSION['userTokens'][$key] < 600)
                ) {
                    unset($_SESSION['userTokens'][$key]);
                    
                    return true;
                }
                
                return false;
            }
            
            public static function generatePassword($number) 
            {
                $result = '';
                
                $arr = [
                    'a','b','c','d','e','f',
                    'g','h','i','j','k','l',
                    'm','n','o','p','r','s',
                    't','u','v','x','y','z',
                    'T','U','V','X','Y','Z',
                    '1','2','3','4','5','6',
                    '7','8','9','0'
                ];
                
                for($i = 0; $i < $number; $i++) {
                    $index = rand(0, count($arr) - 1);
                    $result .= $arr[$index];
                }
                
                return $result;
            }
        }
    }