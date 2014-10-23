<?php

include_once $locator->find("TextSnippet");
include_once $locator->find("Snippet");

class Renderer
{
    public $page;
    
    private $components = array();
    
    function gen(Page $page, $debug = null)
    {
        //do some setup
        $this->page = $page;
        
        $this->components = $page->getComponents();
        $this->components['title'] = new TextSnippet(APP_NAME . ' ~ ' . $page->getTitle());
        $this->components['content'] = $page;
        if($debug != null)
        {
            $this->components['debug'] = $debug;
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
        //restore_error_handler();
        
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
    
    /*function header()
    {
        //include $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
        (new Header())->genContent();
    }
    
    function navbar()
    {
        (new NavBar())->genContent();
    }
    
    function title()
    {
        echo $this->title;
    }
    
    function content()
    {
        $this->page->genContent();
    }
    
    function footer()
    {
        (new Footer())->genContent();
    }*/
}
?>
