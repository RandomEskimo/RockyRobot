<?

    $locator->inc("Snippet");
    
    class Page
    {
        private $title;
        private $snippets = array();
        private $access_level;
        private $force_ssl;
        private $components = array();
        private $template = "defaultTemplate";
        public $Auth;
        
        public function __construct($title)
        {
            $this->title = $title;
            $this->access_level = "admin"; //set as admin by default, better to be too safe
            $this->force_ssl = false;
            $this->Auth = new Authenticator();
            
        }
        
        public function setTitle($title)
        {
            $this->title = $title;
        }
        
        public function addSnippet(Snippet $snippet)
        {
            $this->snippets[] = $snippet;
        }
        
        public function setAccessLevel($level)
        {
            $this->access_level = $level;
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
            if(!$this->Auth->isLoggedIn() && !$this->Auth->meetsPermission($this->Auth->getUserLevel(), $this->access_level) && $this->access_level != 'everyone')
            {
                //include $_SERVER['DOCUMENT_ROOT'] . '/templates/access_denied.php';
                return;
            }   
            for($i = 0;$i < count($this->snippets);++$i)
            {
                $this->snippets[$i]->genContent();
            }
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
            return $this->components;
        }
        
        public function getTemplate()
        {
            return $this->template;
        }
        
        public function setTemplate($template)
        {
            $this->template = $template;
        }
    }
?>
