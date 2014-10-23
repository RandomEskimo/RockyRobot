<?php
    include_once $locator->find("Snippet");

    class ImageSnippet extends Snippet
    {
        private $image;
        private $width;
        private $height;
        private $alt = "";
    
        public function __construct($image, $width, $height)
        {
            $this->image = $image;
            $this->width = $width;
            $this->height = $height;
        }
    
        public function setAlt($text)
        {
            $this->alt = $text;
        }
    
        public function genContent()
        {
            echo '<img src="' . $this->image . '" style="';
            if($this->width != 0)
                echo 'width:' . $this->width . 'px; ';
            if($this->height != 0)
                echo 'height:' . $this->height . 'px;';
            echo '"';
            if($this->alt != "")
                echo ' alt="' . $this->alt . '"';
            echo ' />';
        }
    }
?>
