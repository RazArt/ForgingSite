<?php
    namespace Engine
    {
        class View
        {
            private static $_instance;
            private $_tplDir;
            private $_vars = [];
            private $_params = [];
            
            const RT_SIMPLE_HTML = 0;
            const RT_HTML = 1;
            const RT_DATA = 2;
            const RT_ERROR = 3;
            const RT_MESSAGE= 4;
            
            static public function instance()
            {
                return is_null(self::$_instance)
                    ? self::$_instance = new static()
                    : self::$_instance;
            }

            function __construct()
            {
                self::setParam('stripTags', false);
            }
      
            public function __set($key, $value)
            {
                if (isset($this -> _vars[$key])) {
                    unset($this -> _vars[$key]);
                }
                
                self::_validateVar($value);
                
                $this -> _vars[$key] = $value;
            }
      
            public function __get($key)
            {
                if (!isset($this -> _vars[$key])) {
                    return;
                }
                
                return $this -> _vars[$key];
            }
            
            private function _validateVar(&$value) 
            {
                if (is_array($value)) {
                    foreach ($value as &$array) {
                        $this -> _validateVar($array);
                    }
                } elseif (is_string($value)) {
                    $value = htmlspecialchars($value);
                }
            }
            
            public function __isset($key) 
            {
                return isset($this -> _vars[$key]);
            }
            
            public function __unset($key) 
            {
                unset($this -> _vars[$key]);
            }

            public function setDir($dir)
            {
                $this -> _tplDir = str_replace(array('\\', '/'), D_S, trim($dir, '/\\')) . D_S;
            }
            
            public function setParam($key, $value)
            {
                if (isset($this -> _params[$key])) {
                    unset($this -> _params[$key]);
                }
                
                $this -> _params[$key] = $value;
            }
            
            public function getParam($key)
            {
                return isset($this -> _params[$key]) 
                    ? $this -> _params[$key] 
                    : null;
            }
            
            public function getTpl($template, $tDir = null)
            {
                if (!empty($tDir)) {
                    $tDir = str_replace(array('\\', '/'), D_S, trim($tDir, '/\\')) . D_S;
                } else {
                    $tDir = $this -> _tplDir;
                }
                
                $template = $tDir . $template;
                
                $tplFilePath = TEMPLATE_PATH . $template . '.tpl';
                
                if (!file_exists($tplFilePath)) {
                    return false;
                }
                
                ob_start();
                
                Loader::loadFile($tplFilePath);
                
                $tBody = ob_get_clean();
                
                if ($this -> getParam('stripTags')) {
                    $tBody = $this -> _strip($tBody);
                }
                
                return $tBody;
            }
            
            public function sendResponse($template = null, $tDir = null, $rType = self::RT_HTML, $data = null)
            {
                header('Content-Type: application/json');
                
                switch ($rType)  {
                    case self::RT_HTML:
                        if (!$tBody = self::getTpl($template, $tDir)) {
                            return false;
                        }

                        echo json_encode([
                            'type' => 'html', 
                            'data' => $tBody]
                        );
                        break;
                        
                    case self::RT_ERROR:
                        if (!$tBody = self::getTpl($template, 'errors')) {
                            return false;
                        }
                        
                        echo json_encode([
                            'type' => 'error', 
                            'data' => $tBody
                        ]);
                        break;
                    case self::RT_MESSAGE:
                    
                        if (!$tBody = self::getTpl($template, 'msg')) {
                            return false;
                        }
                        
                        echo json_encode([
                            'type' => 'msg', 
                            'data' => $tBody
                        ]);
                        break;
                        
                    case self::RT_DATA:
                        echo json_encode([
                            'type' => 'data', 
                            'data' => $data
                        ]);
                        break;
                }
            }
            
            private function _strip($data)
            {
                $lit = ["\t", "\n", "\n\r", "\r\n", "  "];
                
                $sp = ['', '', '', '', ''];
                
                return str_replace($lit, $sp, $data);
            }
        }
    }