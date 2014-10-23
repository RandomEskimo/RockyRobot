<?php
    include_once $locator->find("Snippet");
    
    class LinkSnippet extends Snippet
    {
        private $sub;
        private $link;
        
        public function __construct(Snippet $sub, $link)
        {
            $this->sub = $sub;
            $this->link = $link;
        }
        
        public function genContent()
        {
            echo '<a href="' . $this->link . '">';
            $this->sub->genContent();
            echo '</a>';
        }
    }


?>
