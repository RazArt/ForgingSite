<?php
    namespace Model
    {
        class Online
        {
            public function getOnlineList() 
            {
                $cacheName = 'onlineList';
                
                if (!$onlineList = \Engine\Cache::loadCache($cacheName))  {
                    try {
                        $sth =  \App\Db::getConnection('characters', \Engine\Registry::instance() -> selectedRealmId) 
                            -> prepare('SELECT guid, name, race, class, gender, level, zone FROM characters WHERE online = \'1\'');
                        
                        $sth -> execute();
                        
                        if (!$onlineList = $sth -> fetchall(\PDO::FETCH_ASSOC)) {
                            return false;
                        }
                        
                        \Engine\Cache::saveCache($cacheName, $onlineList);
                        
                        return $onlineList;
                    } catch (Exception $e) {
                        return false;
                    }
                } else {
                    return $onlineList;
                }
            }
        }
    }