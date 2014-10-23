<?php

include_once $locator->find("Snippet");

    class TextSnippet extends Snippet
    {
    
        private $text;
        private $dont_filter;
        
        public function __construct($text)
        {
            $this->text = $text;
            $this->dont_filter = false;
        }
        
        public function setDontFilter($dont_filter)
        {
            $this->dont_filter = $dont_filter;
        }
        
        public function setText($text)
        {
            $this->text = $text;
        }
        
        public function getText()
        {
            return $text;
        }
    
        public function genContent()
        {
            if($this->dont_filter)
                echo $this->text;
            else
            {
                $text = htmlentities($this->text);
                echo nl2br($text);
            }
        }
    }
?>
