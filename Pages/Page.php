<?

    $locator->inc("Snippet");
    $locator->inc("FlashSnippet");
    $locator->inc("ScriptsSnippet");
    
    class Page
    {
        private $title;
        private $snippets = array();
        private $ad_snippets = array(); //access denied snippets
        private $access_level;
        private $force_ssl;
        private $components = array();
        private $template = "defaultTemplate";
        private $scripts = array();
        private $style = "style";
        public $Auth;
        
        public function __construct($title)
        {
            $this->title = $title;
            $this->access_level = "admin"; //set as admin by default, better to be too safe
            $this->force_ssl = false;
            $this->Auth = new Authenticator();
            if(isset($_SESSION['flash_message']) && $_SESSION['flash_message'] != null)
            {
                $this->components['flash'] = new FlashSnippet($_SESSION['flash_message']);
                $_SESSION['flash_message'] = null;
            }
        }
        
        public function setTitle($title)
        {
            $this->title = $title;
        }
        
        public function addSnippet(Snippet $snippet)
        {
            $this->snippets[] = $snippet;
        }
        
        public function addAccessDeniedSnippet(Snippet $snippet)
        {
            $this->ad_snippets[] = $snippet;
        }
        
        public function setAccessLevel($level)
        {
            $this->access_level = $level;
        }
        
        public function getAccessLevel()
        {
            return $this->access_level;
        }
        
        public function setForceSSL($force)
        {
            $this->force_ssl = $force;
        }
        
        public function isForceSSL()
        {
            return $this->force_ssl;
        }
        
        public function genContent()
        {
            foreach($this->snippets as $snippet)
                $snippet->genContent();
        }
        
        public function genAccessDenied()
        {
            foreach($this->ad_snippets as $snippet)
                $snippet->genContent();
        }
        
        public function getTitle()
        {
            return $this->title;
        }
        
        public function setComponent($name, Snippet $component)
        {
            $this->components[$name] = $component;
        }
        
        public function getComponents()
        {
            $out = $this->components;
            $out['scripts'] = new ScriptsSnippet($this->scripts);
            return $out;
        }
        
        public function getTemplate()
        {
            return $this->template;
        }
        
        public function setTemplate($template)
        {
            $this->template = $template;
        }
        
        public function setFlashMessage($message)
        {
            $this->components['flash'] = new FLashSnippet($message);
        }
        
        public function addScript($script)
        {
            $this->scripts[] = $script;
        }
    }
?>
