<?php
    namespace App
    {
        class Account
        {
            const ACCESS_LEVEL_USER = 1;
            const ACCESS_LEVEL_MODERATOR = 2;
            const ACCESS_LEVEL_ADMINISTRATOR = 3;
            
            private static $_account = [];
            private static $_accessLevel = 0;
            
            static public function init()
            {
                if (isset($_SESSION['user'])) {
                    if (!isset($_SESSION['user']['ip']) 
                        || $_SESSION['user']['ip'] != $_SERVER['REMOTE_ADDR']
                    ) {
                        unset($_SESSION['user']);
                    }
                    
                    if (!isset($_SESSION['user']['userAgent'])
                        || $_SESSION['user']['userAgent'] != $_SERVER['HTTP_USER_AGENT']
                    ) {
                        unset($_SESSION['user']);
                    }
                    
                    self::$_account['id'] = $_SESSION['user']['id'];
                    self::$_account['name'] = $_SESSION['user']['name'];
                        
                    if (!Db::getConnection('system')) {
                        return false;
                    }
                    
                    $sth = Db::getConnection('system') -> prepare('SELECT `level` FROM `' . \Engine\Config::instance() -> mysql['prefix'] . 'access` WHERE `id` = :id');
                    
                    $sth -> bindValue(':id', self::$_account['id']);
                    
                    $sth -> execute();
                    
                    $administrateLevel = (int)$sth -> fetch(\PDO::FETCH_ASSOC)['level'];
                    
                    if ($administrateLevel == 1) {
                        self::$_accessLevel = self::ACCESS_LEVEL_MODERATOR;
                    } elseif ($administrateLevel == 2) {
                        self::$_accessLevel = self::ACCESS_LEVEL_ADMINISTRATOR;
                    } else {
                        self::$_accessLevel = self::ACCESS_LEVEL_USER;
                    }
                }
            }
            
            static public function checkAccessLevel($requiredLevel) 
            {
                return self::$_accessLevel >= $requiredLevel
                    ? true : false;
            }
            
            static public function getId() 
            {
                return isset($_SESSION['user'])
                    ? self::$_account['id'] : false;
            }
            
            static public function getUserName() 
            {
                return isset($_SESSION['user']) 
                    ? self::$_account['name'] : false;
            }
            
            static public function getEmail() 
            {
                return isset($_SESSION['user']) 
                    ? self::$_account['email'] : false;
            }
            
            static public function setEmail($email) 
            {
                try {
                    $sth = Db::getConnection('auth') -> prepare('UPDATE `account` '
                                                              . 'SET `email` = :email '
                                                              . 'WHERE `id` = :id');
                                                              
                    $sth -> bindValue(':email', $email);
                    $sth -> bindValue(':id', self::$_account['id']);
                    
                    $sth -> execute();
                    
                    if ($sth -> rowCount() != 1) {
                        return false;
                    }
                    
                    return true;
                } catch (Exception $e) {
                        return false;
                }
            }
            
            static public function getMoneyCount() 
            {
                if (isset($_SESSION['user'])) {
                    try {
                        $sth = Db::getConnection('system') -> prepare(
                            'SELECT money FROM `' . \Engine\Config::instance() -> mysql['prefix'] . 'money` '
                          . 'WHERE `id` = :id '
                          . 'LIMIT 1'
                        );
                        
                        $sth -> bindValue(':id', self::$_account['id']);
                        
                        $sth -> execute();
                        
                        if (!$money = $sth -> fetch(\PDO::FETCH_NUM)) {
                            return 0;
                        }
                        
                        return (int)$money[0];
                    } catch (Exception $e) {
                        return 0;
                    }
                }
                
                return 0;
            }
            
            static public function getCharactersList() 
            {
                if (isset($_SESSION['user'])) {
                    $cacheName = 'account' . self::$_account['id'] . 'CharlistRealm' . \Engine\Registry::instance() -> selectedRealmId;
                
                    if (!$charactersList = \Engine\Cache::loadCache($cacheName)) {
                        try {
                            $sth = Db::getConnection('characters', \Engine\Registry::instance() -> selectedRealmId) 
                                -> prepare(
                                    'SELECT guid, name, level, gender, race, class FROM `characters` '
                                  . 'WHERE `account` = :id '
                            );
                            
                            $sth -> bindValue(':id', self::$_account['id']);
                            
                            $sth -> execute();
                            
                            foreach ($sth -> fetchall(\PDO::FETCH_ASSOC) as $character) {
                                $charactersList[$character['guid']] = $character;
                            }
                         
                            if (empty($charactersList))    {
                                return false;
                            }
                            
                            \Engine\Cache::saveCache($cacheName, $charactersList);
                            
                            return $charactersList;
                        } catch (Exception $e) {
                            return false;
                        }
                    } else {
                        return $charactersList;
                    }
                }
                
                return false;
            }
        }
    }