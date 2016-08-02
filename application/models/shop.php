<?php
    namespace Model
    {
        class Shop
        {
            public function getCategoriesList() 
            {
                $cacheName = 'shopCategoriesList';
                
                if (!$categoriesList = \Engine\Cache::loadCache($cacheName)) {
                    try {
                        $sth = \App\Db::getConnection('system') -> 
                            prepare('SELECT * FROM `' . \Engine\Config::instance() -> mysql['prefix'] . 'shop_categories` '
                                  . 'WHERE `realm` = :realm OR `realm` = \'-1\'');
                        
                        $sth -> bindValue(':realm', \Engine\Registry::instance() -> selectedRealmId);
                      
                        $sth -> execute();
                        
                        if (!$categoriesList = $sth -> fetchall(\PDO::FETCH_ASSOC)) {
                            return false;
                        }
                        
                        foreach ($categoriesList as $category) {
                            $newCategoriesList[$category['id']] = $category;
                        }
                        
                        \Engine\Cache::saveCache($cacheName, $newCategoriesList);
                        
                        return $newCategoriesList;
                    } catch (Exception $e) {
                        return false;
                    }
                } else {
                    return $categoriesList;
                }
            }
            
            public function getCosts()
            {
                $cacheName = 'shopCosts';
                
                if (!$costs = \Engine\Cache::loadCache($cacheName)) {
                    try {
                        $sth = \App\Db::getConnection('system') -> 
                            prepare('SELECT * FROM `' . \Engine\Config::instance() -> mysql['prefix'] . 'costs`');
                    
                        $sth -> execute();
                    
                        if (!$tempCosts = $sth -> fetchall(\PDO::FETCH_ASSOC)) {
                            return false;
                        }
                        
                        foreach ($tempCosts as $cost) {
                            $costs[$cost['operation']] = $cost['cost'];
                        }
                            
                        \Engine\Cache::saveCache($cacheName, $costs);
                        
                        return $costs;
                    } catch (Exception $e) {
                        return false;
                    }
                } else {
                    return $costs;
                }
            }
            
            public function getItemsListFromCategory($category) 
            {
                $cacheName = 'shopItemesListCategory' . $category . 'Realm' . \Engine\Registry::instance() -> selectedRealmId;
                
                if (!$itemsList = \Engine\Cache::loadCache($cacheName)) {
                    try {
                        $sth = \App\Db::getConnection('system') -> 
                            prepare('SELECT * FROM `' . \Engine\Config::instance() -> mysql['prefix'] . 'shop_items`, '
                                  . '`' . \Engine\Config::instance() -> mysql['prefix'] . 'locales_item` '
                                  . 'WHERE `category` = :category AND (`realm` = :realm OR `realm` = \'-1\') '
                                  . 'AND `' . \Engine\Config::instance() -> mysql['prefix'] . 'shop_items`.`entry` = `' . \Engine\Config::instance() -> mysql['prefix'] . 'locales_item`.`entry`');
                        
                        $sth -> bindValue(':category', $category);
                        $sth -> bindValue(':realm', \Engine\Registry::instance() -> selectedRealmId);
                        
                        $sth -> execute();
                        
                        if (!$itemsList = $sth -> fetchall(\PDO::FETCH_ASSOC)) {
                            return false;
                        }
                        
                        \Engine\Cache::saveCache($cacheName, $itemsList);
                        
                        return $itemsList;
                    } catch (Exception $e) {
                        return false;
                    }
                } else {
                    return $itemsList;
                }
            }
            
            public function getItemInfo($id) 
            {
                try {
                    $sth = \App\Db::getConnection('system') -> 
                        prepare('SELECT id, entry, stack_count, cost FROM `' . \Engine\Config::instance() -> mysql['prefix'] . 'shop_items` '
                              . 'WHERE `id` = :id AND (`realm` = :realm OR `realm` = \'-1\') '
                              . 'LIMIT 1');
                    
                    $sth -> bindValue(':id', $id);
                    $sth -> bindValue(':realm', \Engine\Registry::instance() -> selectedRealmId);
                    
                    $sth -> execute();
                    
                    if (!$item = $sth -> fetchall(\PDO::FETCH_ASSOC)) {
                        return false;
                    }
                    
                    return $item[0];
                } catch (Exception $e) {
                    return false;
                }
            }
            
            public function addItemToCart($entry, $count, $cost) 
            {
                $dbSystem = \App\Db::getConnection('system');
                $dbSystem -> beginTransaction();
                
                try {
                    $sth = $dbSystem -> prepare('INSERT INTO `' . \Engine\Config::instance() -> mysql['prefix'] . 'money` '
                                              . 'SET `id` = :id, `money` = `money` - :cost '
                                              . 'ON DUPLICATE KEY UPDATE `money` = `money` - :cost');
                    
                    $sth -> bindValue(':id', \App\Account::getId());
                    $sth -> bindValue(':cost', $cost);
                    
                    $sth -> execute();
                    
                    $sth = $dbSystem -> prepare('INSERT INTO `' . \Engine\Config::instance() -> mysql['prefix'] . 'shop_cart` '
                                              . 'SET `account` = :account, `entry` = :entry,  `count` = :count, `realm` = :realm');
                        
                    $sth -> bindValue(':account', \App\Account::getId());
                    $sth -> bindValue(':entry', $entry);
                    $sth -> bindValue(':count', $count);
                    $sth -> bindValue(':realm', \Engine\Registry::instance() -> selectedRealmId);
                    
                    $sth -> execute();
                    
                    $dbSystem -> commit();
                    
                    return true;
                } catch (Exception $e) {
                    $dbSystem -> rollBack();
                    
                    return false;
                }
            }
            
            public function getItemsFromCart() 
            {
                try
                {
                    $sth = \App\Db::getConnection('system') -> 
                        prepare('SELECT * FROM `' . \Engine\Config::instance() -> mysql['prefix'] . 'shop_cart`, '
                                  . '`' . \Engine\Config::instance() -> mysql['prefix'] . 'locales_item` '
                                  . 'WHERE `account` = :account AND `realm` = :realm '
                                  . 'AND `' . \Engine\Config::instance() -> mysql['prefix'] . 'shop_cart`.`entry` = `' . \Engine\Config::instance() -> mysql['prefix'] . 'locales_item`.`entry`');
                                                
                    $sth -> bindValue(':account', \App\Account::getId());
                    $sth -> bindValue(':realm', \Engine\Registry::instance() -> selectedRealmId);
                    
                    $sth -> execute();
                    
                    if (!$itemsList = $sth -> fetchall(\PDO::FETCH_ASSOC)) {
                        return false;
                    }
                    
                    return $itemsList;
                }
                catch (Exception $e)
                {
                    return false;
                }
            }
            
            public function getItemFromCart($id) 
            {
                try {
                    $sth = \App\Db::getConnection('system') -> 
                        prepare('SELECT * FROM `' . \Engine\Config::instance() -> mysql['prefix'] . 'shop_cart` '
                                   . 'WHERE `id` = :id AND `account` = :account AND (`realm` = :realm OR `realm` = \'-1\')');
                                               
                    $sth -> bindValue(':id', $id);
                    $sth -> bindValue(':account', \App\Account::getId());
                    $sth -> bindValue(':realm', \Engine\Registry::instance() -> selectedRealmId);
                    
                    $sth -> execute();
                    
                    if (!$item = $sth -> fetchall(\PDO::FETCH_ASSOC)) {
                        return false;
                    }
                    
                    return $item[0];
                } catch (Exception $e) {
                    return false;
                }
            }
            
            public function sendItem($id, $entry, $count, $guid) 
            {
                $dbSystem = \App\Db::getConnection('system');
                $dbCharacters = \App\Db::getConnection('characters');
                
                $dbSystem -> beginTransaction();
                $dbCharacters -> beginTransaction();
                    
                try {
                    $sth = $dbCharacters -> prepare('INSERT INTO `mail_external` '
                                                                   . '(`receiver`, `item`, `item_count`)'
                                                                   . 'VALUES (:guid, :entry, :count)');
                    
                    $sth -> bindValue(':guid', $guid);
                    $sth -> bindValue(':entry', $entry);
                    $sth -> bindValue(':count', $count);
                    
                    $sth -> execute();
                    
                    $sth = $dbSystem -> prepare('DELETE FROM `' . \Engine\Config::instance() -> mysql['prefix'] . 'shop_cart` '
                                                              . 'WHERE `id` = :id');
                                               
                    $sth -> bindValue(':id', $id);
                    
                    $sth -> execute();
                    
                    $dbSystem -> commit();
                    $dbCharacters -> commit();
                    
                    return true;
                } catch (Exception $e) {
                    $dbSystem -> rollBack();
                    $dbCharacters -> rollBack();
                    
                    return false;
                }
            }
            
            public function setAtlogin($guid, $type) 
            {
                $dbSystem = \App\Db::getConnection('system');
                $dbCharacters = \App\Db::getConnection('characters');
                
                $dbSystem -> beginTransaction();
                $dbCharacters -> beginTransaction();
                
                try {
                    $cost = self::getCosts()[$type];
                    
                    if ($type == 'faction') {
                        $atLogin = '64';
                    } elseif ($type == 'race') {
                        $atLogin = '128';
                    } elseif ($type == 'name') {
                        $atLogin = '1';
                    } elseif ($type == 'customize') {
                        $atLogin = '8';
                    } else {
                        return false;
                    }
                    
                    $sth = $dbSystem -> prepare('INSERT INTO `' . \Engine\Config::instance() -> mysql['prefix'] . 'money` '
                                                              . 'SET `id` = :id, `money` = `money` - :cost '
                                                              . 'ON DUPLICATE KEY UPDATE `money` = `money` - :cost');
                    
                    $sth -> bindValue(':id', \App\Account::getId());
                    $sth -> bindValue(':cost', $cost);
                    
                    $sth -> execute();
                    
                    $sth = $dbCharacters -> prepare('UPDATE `characters` '
                                                                   . 'SET `at_login` = :atLogin '
                                                                   . 'WHERE `guid` = :guid');
                    
                    $sth -> bindValue(':guid', $guid);
                    $sth -> bindValue(':atLogin', $atLogin);
                    
                    $sth -> execute();
                    
                    $dbSystem -> commit();
                    $dbCharacters -> commit();
                    
                    return true;
                } catch (Exception $e) {
                    $dbSystem -> rollBack();
                    $dbCharacters -> rollBack();
                    
                    return false;
                }
            }
        }
    }