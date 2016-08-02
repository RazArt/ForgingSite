<?php
    namespace Engine
    {
        class Cache
        {
            static private function _getCacheFilePath($cacheName)
            {
                return CACHE_PATH . 'cache.' . sha1(strtolower($cacheName) . 'cache') . '.tmp';
            }
            
            static public function saveCache($cacheName, $cacheVars)
            {
                $cacheFilePath = self::_getCacheFilePath($cacheName);
                
                if (!is_array($cacheVars)) {
                    return false;
                }
                
                return return Json::save($cacheFilePath, $cacheVars);
            }
            
            static public function loadCache($cacheName)
            {
                $cacheFilePath = self::_getCacheFilePath($cacheName);
                
                return Json::load($cacheFilePath);
            }
            
            static public function cacheExists($cacheName)
            {
                $cacheFilePath = self::_getCacheFilePath($cacheName);
                
                if (!file_exists($cacheFilePath)
                    || !is_readable($cacheFilePath)
                    || (time() - filemtime($cacheFilePath)) > 3600
                ) {
                    return false;
                }
                
                return true;
            }
            
            static public function flushCache()
            {
                if ($dirHandle = opendir(CACHE_PATH)) {
                    while (($fileName = readdir($dirHandle)) !== false) {
                        if (strlen($fileName) > 2)     {
                            unlink(CACHE_PATH . $fileName);
                        }
                    }
                   
                    closedir($dirHandle);
                   
                    return true;
                }
                
                return false;
            }
        }
    }
