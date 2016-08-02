<?php
    namespace App
    {
        class Db
        {
            static private $_connections = [];
            
            static public function getConnection($dbName, $realmId = null)
            {
                if ($realmId == null &&
                    ($dbName == 'characters' 
                    || $dbName == 'world' 
                    || $dbName == 'auth')
                ){
                    $realmId = \Engine\Registry::instance() -> selectedRealmId;
                    $connectionName = $dbName . '_' . $realmId;
                } else {
                    $connectionName = $dbName;
                }
                
                if (!isset(self::$_connections[$connectionName]) 
                    || !self::$_connections[$connectionName]
                ) {
                    
                    if ($dbName == 'system') {
                        $mysqlConfig = explode(';', \Engine\Config::instance() -> mysql['config']);
                    } elseif ($dbName == 'auth'
                        || $dbName == 'characters'
                        || $dbName == 'world'
                    ) {
                        $mysqlConfig[0] = \Engine\Config::instance() -> realms[$realmId]['mysql']['address'];
                        $mysqlConfig[1] = \Engine\Config::instance() -> realms[$realmId]['mysql']['port'];
                        $mysqlConfig[2] = \Engine\Config::instance() -> realms[$realmId]['mysql']['user'];
                        $mysqlConfig[3] = \Engine\Config::instance() -> realms[$realmId]['mysql']['password'];
                        $mysqlConfig[4] = \Engine\Config::instance() -> realms[$realmId]['mysql']['encoding'];
                        $mysqlConfig[5] = \Engine\Config::instance() -> realms[$realmId]['mysql'][$dbName . 'DbName'];
                    }
                    
                    if (!$mysqlConfig) {
                        return false;
                    }
                    
                    try {
                        self::$_connections[$connectionName] = new \PDO(
                            'mysql:host=' . $mysqlConfig[0]
                          . ';port=' . $mysqlConfig[1]
                          . ';dbname=' . $mysqlConfig[5], 
                            $mysqlConfig[2], 
                            $mysqlConfig[3],
                            [\PDO::ATTR_PERSISTENT => true]
                        );
                        
                        self::$_connections[$connectionName] -> query('SET NAMES ' . $mysqlConfig[4]);
                        
                        self::$_connections[$connectionName] -> setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
                        self::$_connections[$connectionName] -> setAttribute(\PDO::ATTR_EMULATE_PREPARES, 1);
                        
                        return self::$_connections[$connectionName];
                    } catch(\Exception $e) {
                        return false;
                    }
                }
                
                return self::$_connections[$connectionName];
            }
        }
    }
