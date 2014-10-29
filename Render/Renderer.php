<?php

$locator->inc("TextSnippet");
$locator->inc("Snippet");

class Renderer
{
    
    private $components = array();
    
    function gen(Page $page)
    {
        //do some setup
        
        $this->components = $page->getComponents();
        $this->components['title'] = new TextSnippet(APP_NAME . ' ~ ' . $page->getTitle());
        $this->components['content'] = $page;
        $this->components['rrversion'] = new TextSnippet(__VERSION__);
        if(DEBUG)
        {
            $this->components['debug'] = new DebugSnippet();
        }
        
        $locator = new ResourceLocator();
        
        $file = $locator->find($page->getTemplate());
        
        //include the template
        set_error_handler(function($errno, $errstr, $errfile, $errline)
        {
            global $file;
            echo "<h2>Render errro</h2>Error including file: \"" . $file . "\"<br/>";
            echo "[" . $errno . "]: " . $errstr . ". in: " . $errfile . "[" . $errline . "]<br/>";
            //no need to exit
            exit();
        });
        include $file;
        restore_error_handler();
        
    }
    
    function get($component)
    {
        //
        if(array_key_exists($component, $this->components))
        {
            $this->components[$component]->genContent();
            //echo $component;
        }
    }
}
?>
