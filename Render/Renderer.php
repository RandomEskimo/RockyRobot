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
        
        $template = $locator->find($page->getTemplate());
        
        //include the template
        set_error_handler(function()
        {
            global $template;
            echo "Unable to find template file: \"" . $template . "\"";
            exit();
        });
        include $template;
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
