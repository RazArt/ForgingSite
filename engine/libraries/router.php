<?php
    namespace Engine
    {
        class Router
        {
            static private $_routerRules = [];
            
            static public function addRouterRule($controllerPath, $defaultAction = 'index')
            {
                $rule = &self::$_routerRules[];
                $controllerPath = trim($controllerPath, '/\\');
                $rule['pattern'] = mb_convert_case($controllerPath, MB_CASE_LOWER);
                $rule['name'] = 'Controller';
                foreach (explode('\\', $controllerPath) as $value) {
                    $rule['name'] .=  '\\' . mb_convert_case($value, MB_CASE_TITLE);
                }
                $rule['controllerPath'] = CONTROLLERS_PATH . str_replace('\\', D_S, $controllerPath)  . '.php';
                $rule['templatePath'] = str_replace('\\', D_S, $controllerPath);
                $rule['defaultAction'] = $defaultAction;
                var_dump($rule);
            }
            
            static private function _getParsedArguments($arguments)
            {
                if (!is_array($arguments)) {
                    return false;
                }
                
                foreach ($arguments as $value) {
                    if (preg_match_all('/^([a-zA-Z0-9_]+)=(.*)$/', $value, $matches)) {
                        $pArguments[$matches[1][0]] = $matches[2][0];
                    }
                }
                
                if (!isset($pArguments) 
                   || !is_array($pArguments)
                ) {
                    return false;
                }
                
                return $pArguments;
            }
            
            static private function getControllerInfo($route)
            {
                $route = str_replace('\\', '/', trim($route, '/\\') . '/');
                
                foreach (self::$_routerRules as $value) {
                    if (preg_match_all($value['pattern'], $route, $matches)) {
                        $info = $value;
                        $info['Action'] = explode('/', (trim($matches[1][0], '/\\')))[0];
                        $info['controllerArguments'] = self::_getParsedArguments(explode('/', (trim($matches[1][0], '/\\'))));
                        
                        return $info;
                    }
                }
                
                return false;
            }
            
            static public function loadController($routingString = false)
            {
                if ($routingString == false && !$routingString = Request::get('route', Request::TYPE_STRING)) {
                    $routingString = 'main';
                }
                
               /* if (!$controllerInfo = self::getControllerInfo($routingString)) {
                    return false;
                }*/
                
                if (!file_exists($controllerInfo['controllerPath'])) {
                    return false;
                }
                
                $controllerName = $controllerInfo['name'];
                
                $controller = new $controllerName();
                
                if (is_callable([$controller, 'action_' . $controllerInfo['Action']])) {
                    $cAction = 'action_' . $controllerInfo['Action'];
                } else {
                    $cAction = 'action_' . $controllerInfo['defaultAction'];
                }
                
                $controller -> $cAction($controllerInfo['controllerArguments']);
            }
        }
    }