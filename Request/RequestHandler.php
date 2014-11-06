<?php
    class RequestHandler
    {
        private $controller = 'index';
        private $function = 'index';
        private $raw_controller;
        private $raw_request;
        private $args = array();
        
        public function __construct($uri)
        {
            $raw_request = $uri;
            //break up the request into parts
            $parts = explode("/", $uri);
            $parts = array_slice($parts, 1);
            if(count($parts) != 0 && $parts[0] != "")
            {
                $this->controller = $parts[0];
                if(count($parts) > 1 && $parts[1] != "")
                    $this->function = $parts[1];
                if(count($parts) > 2 && $parts[2] != "")
                    $this->args = array_slice($parts, 2);
            }
            
            $this->raw_controller = $this->controller;
            $this->controller = $this->makeControllerName($this->controller);
        }
    
        public function handleRequest()
        {
            global $file;
            global $auth;
            global $locator;
            $file = $this->controller;
            if(ctype_alnum($this->controller) && ctype_alnum($this->function))
            {
                set_error_handler(function($errno, $errstr, $errfile, $errline)
                {
                    global $file;
                    $error = "Unable to find controller: \"" . $file . "\"<br/>" .
                        "[" . $errno . "]: " . $errstr . ". in: " . $errfile . "[" . $errline . "]<br/>";
                    Renderer::renderError($error);
                    exit();
                });
                include_once $locator->find($file);
                restore_error_handler();
                
                //instanciate the controller
                $cont = eval(" return new " . $this->controller . "();");

                //make sure it has the function
                if(!method_exists($cont, $this->function))
                {
                    $error = "Controller: \"" . $this->controller . "\" has no function: " . $this->function . "()";
                    Renderer::renderError($error);
                    exit();
                }
                
                //add any objects needed by the controller
                $cont->Auth = $auth;
                
                set_error_handler(function($errno, $errstr, $errfile, $errline)
                {
                    $error = "Error when calling function<br/>" .
                        "[" . $errno . "]: " . $errstr . ". in: " . $errfile . "[" . $errline . "]<br/>";
                    Renderer::renderError($error);
                    exit();
                });
                $result = eval('return $cont->' . $this->function . '($this->args);');
                restore_error_handler();
                return $result;
                
            }
            else
            {
                Renderer::renderError("Controller or function incorrectly named");
                exit();
            }
        }
        
        public function getRawController()
        {
            return $this->raw_controller;
        }
        
        public function getController()
        {
            return $this->controller;
        }
        
        public function getArgs()
        {
            return $this->args;
        }
        
        public function getRawRequest()
        {
            return $this->rawRequest();
        }
        
        public function getFunction()
        {
            return $this->function;
        }
        
        private function makeControllerName($name)
        {
            $out = "";
            $first = substr($name, 0, 1);
            $out .= strtoupper($first);
            $out .= substr($name, 1) . "Controller";
            return $out;
        }
    }
?>
