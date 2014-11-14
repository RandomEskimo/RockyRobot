<?php
    class RawHtmlSnippet extends Snippet
    {
        private $html;
        
        public function __construct($html)
        {
            $this->html = $html;
        }
        
        public function genContent()
        {
            echo $this->html . "\n";
        }
    }
?>
