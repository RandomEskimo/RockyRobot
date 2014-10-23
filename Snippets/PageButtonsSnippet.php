<?php

    class PageButtonsSnippet extends Snippet
    {
        private $newer;
        private $older;
        private $current_page;
        private $dest;
    
        public function __construct($newer, $older, $current_page, $dest)
        {
            $this->newer = $newer;
            $this->older = $older;
            $this->current_page = $current_page;
            $this->dest = $dest;
        }
    
        public function genContent()
        {
            echo "<div id=\"pagecontrols\"><br />";
            if($this->older)
            {
	            echo "<span class=\"pageleft\"><a href=\"" . $this->dest . "/" . ($this->current_page + 1) . "\">Older posts</a></span>";
            }
            if($this->newer)
            {
	            echo "<span class=\"pageright\"><a href=\"" . $this->dest . "/" . ($this->current_page - 1) . "\">Newer Posts</a></span>";
            }
            echo "</div><br />";
        }
    }
?>
