<?php

$locator->inc("TextSnippet");
$locator->inc("Snippet");

class Renderer
{
    
    private $components = array();
    
    function gen(Page $page)
    {
        global $file;
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
            if(DEBUG)
            {
                echo "<h2>Render error</h2>Error including file: \"" . $file . "\"<br/>";
                echo "[" . $errno . "]: " . $errstr . ". in: " . $errfile . "[" . $errline . "]<br/>";
            }
            exit();
        });
        include $file;
        restore_error_handler();
        
    }
    
    function get($component)
    {
        global $file;
        $file = $component;
        set_error_handler(function($errno, $errstr, $errfile, $errlin)
        {
            global $file;
            if(DEBUG)
            {
                echo "<h2>Render::get() error</h2>Error when finding component: \"" . $file . "\"<br/>";
                echo "[" . $errno . "]: " . $errstr . ". in: " . $errfile . "[" . $errline . "]<br/>";
            }
        });
        if(array_key_exists($component, $this->components))
        {
            $this->components[$component]->genContent();
        }
        restore_error_handler();
    }
}
?>
