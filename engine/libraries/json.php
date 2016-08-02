<?php
    namespace Engine
    {
        class Json
        {
            static public function load($filePath)
            {
                if (!file_exists($filePath) 
                    || !is_readable($filePath) 
                ) {
                    return null;
                } else {
                    try {
                        $fileHandle = fopen($filePath, "r");
                        $fileData = fread($fileHandle, filesize($filePath));
                        $vars = json_decode($fileData, true);
                        
                        fclose($fileHandle);
                    } catch(\Exception $e) {
                        return false;
                    }
                    
                    if (!empty($vars)) {
                        return $vars;
                    }
                    
                    return false;
                }
            }
            
            static public function save($filePath, $vars)
            {
                if (!is_array($vars)) {
                    return false;
                }
                
                $vars = json_encode($vars);
                
                try {
                    $fileHandle = fopen($filePath, "w");
                    fwrite($fileHandle, $vars);
                    fclose($fileHandle);
                } catch(\Exception $e) {
                    return false;
                }
                
                return true;
            }
            
            
        }
    }