<?php
    class ResourceLocator
    {
        private $coreSnippets = array('ContainerSnippet', 'DivSnippet',
            'DropMenuSnippet', 'FormSnippet', 'HeadingSnippet',
            'ImageSnippet', 'InputSnippet', 'LinkSnippet', 
            'PageButtonsSnippet', 'TableSnippet', 'TextAreaSnippet',
            'TextSnippet', 'Snippet', 'DebugSnippet'
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
                if(preg_match("/.*Page$/", $resource))
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
                        return $_SERVER['DOCUMENT_ROOT'] . "/core/ClassHandling/AbstractAuthenticator.php";
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
            }
            //others
            if($type == "other")
                return $_SERVER['DOCUMENT_ROOT'] . "/core/Other/" . $resource . ".php";
        }
        
        public function inc($file, $type = null)
        {
            $locator = $this;
            set_error_handler(function()
            {
                global $file;
                echo "Unable to include file: \"" . $file . "\"";
                exit();
            });
            include_once $this->find($file, $type);
            restore_error_handler();
        }
    }
?>
