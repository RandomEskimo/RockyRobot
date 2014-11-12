<?php

$locator->inc("TextSnippet");
$locator->inc("Snippet");

class Renderer
{
    
    private $components = array();
    private $Auth;
    
    public function __construct()
    {
        $this->Auth = new Authenticator();
    }
    
    public function gen(Page $page)
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
        set_error_handler(function($errno, $errstr, $errfile, $errline)
        {
            global $file;
            if(DEBUG)
            {
                echo "<h2>Render::get() error</h2>Error when generating component content: \"" . $file . "\"<br/>";
                echo "[" . $errno . "]: " . $errstr . ". in: " . $errfile . "[" . $errline . "]<br/>";
            }
        });
        if($component == 'content')
        {
            //do access checking
            $page = $this->components[$component];
            $access_level = $page->getAccessLevel();
            if(!$this->Auth->isLoggedIn() && 
                !$this->Auth->meetsPermission($this->Auth->getUserLevel(), 
                    $access_level) && $access_level != 'everyone')
            {
                //access denies]d
                $page->genAccessDenied();
            }
            else
            {
                //access granted
                $page->genContent();
            }
        }
        else if(array_key_exists($component, $this->components))
        {
            $this->components[$component]->genContent();
        }
        restore_error_handler();
    }
    
    public static function renderError($errtext)
    {
        $err_cont = new ErrorController();
        $page;
        if(DEBUG)
            $page = $err_cont->index($errtext);
        else
            $page = $err_cont->index("Internal Error");
        $ren = new Renderer();
        $ren->gen($page);
    }
}
?>
