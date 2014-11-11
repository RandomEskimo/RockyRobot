<?php
    class ResourceLocator
    {
        private $coreSnippets = array('ContainerSnippet', 'DivSnippet',
            'DropMenuSnippet', 'FormSnippet', 'HeadingSnippet',
            'ImageSnippet', 'InputSnippet', 'LinkSnippet', 
            'PageButtonsSnippet', 'TableSnippet', 'TextAreaSnippet',
            'TextSnippet', 'Snippet', 'DebugSnippet', 'FlashSnippet', 
            'ScriptsSnippet', 'BreakSnippet'
        );
        
        private $corePages = array('Page');
    
        public function find($resource, $type = null)
        {
            //if type is null, then we are expected to know where to look for the resource
            if($type == null)
            {
                //work out what sort of resource it is
                //Snippet
                if(preg_match("/.*Snippet$/", $resource))
                {
                    //work out if it is an RR defined snippet or a user defined snippet
                    if(in_array($resource, $this->coreSnippets))
                        return $_SERVER['DOCUMENT_ROOT'] . "/core/Snippets/" . $resource . ".php";
                    return $_SERVER['DOCUMENT_ROOT'] . "/Snippets/" . $resource . ".php";
                }
                //controller
                if(preg_match("/.*Controller$/", $resource))
                {
                    if($resource == "AbstractController")
                        return $_SERVER['DOCUMENT_ROOT'] . "/core/Controllers/AbstractController.php";
                    return $_SERVER['DOCUMENT_ROOT'] . "/Controllers/" . $resource . ".php";
                }
                //Page
                if(preg_match("/.*Pages?$/", $resource))
                {
                    if(in_array($resource, $this->corePages))
                        return $_SERVER['DOCUMENT_ROOT'] . "/core/Pages/" . $resource . ".php";
                    return $_SERVER['DOCUMENT_ROOT'] . "/Pages/" . $resource . ".php";
                }
                //Settings
                if($resource == "Settings")
                    return $_SERVER['DOCUMENT_ROOT'] . "/Settings/Settings.php";
                //Authenticator
                if(preg_match("/.*Authenticator$/", $resource))
                {
                    if($resource == "AbstractAuthenticator")
                        return $_SERVER['DOCUMENT_ROOT'] . "/core/Other/AbstractAuthenticator.php";
                    return $_SERVER['DOCUMENT_ROOT'] . "/RequiredComponents/" . $resource . ".php";
                }
                //Templates
                if(preg_match("/.*Template$/", $resource))
                {
                    return $_SERVER['DOCUMENT_ROOT'] . "/Templates/" . $resource . ".php";
                }
                //Managers
                if(preg_match("/.*Manager$/", $resource))
                    return $_SERVER['DOCUMENT_ROOT'] . "/Managers/" . $resource . ".php";
                //Models
                if(preg_match("/.*Model$/", $resource))
                    return $_SERVER['DOCUMENT_ROOT'] . "/Models/" . $resource . ".php";
                //request handler
                if($resource == 'RequestHandler')
                    return $_SERVER['DOCUMENT_ROOT'] . "/core/Request/" . $resource . ".php";
            }
            //others
            if($type == "other")
            {
                if($resource == "GlobalFunctions")
                    return $_SERVER['DOCUMENT_ROOT'] . "/core/Other/" . $resource . ".php";
                return $_SERVER['DOCUMENT_ROOT'] . "/Other/" . $resource . ".php";
            }
        }
        
        public function inc($resource, $type = null)
        {
            if(is_array($resource))
            {
                foreach($resource as $request)
                {
                    if(is_array($request))
                    {
                        if(isset($request['resource']))
                        {
                            $res = $request['resource'];
                            $ty  = null;
                            if(isset($request['type']))
                                $ty = $request['type'];
                            $this->inc_($res, $ty);
                        }
                    }
                    else
                        $this->inc_($request);
                }
            }
            else
                $this->inc_($resource, $type);
        }
        
        private function inc_($resource, $type = null)
        {
            global $file;
            $locator = $this;
            $file = $resource;
            set_error_handler(function($errno, $errstr, $errfile, $errline)
            {
                global $file;
                $error = "Error including file: \"" . $file . "\"</br>" .
                    "[" . $errno . "]: " . $errstr . ". in: " . $errfile . "[" . $errline . "]<br/>";
                    Renderer::renderError($error);
                exit();
            });
            include_once $this->find($resource, $type);
            restore_error_handler();
        }
    }
?>
