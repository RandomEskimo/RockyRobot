<?php
    include_once $locator->find("TextSnippet");
    
    class HSnippet extends TextSnippet
    {
        private $text;
        private $dont_filter;
        private $level;
        
        public function __construct($text, $level)
        {
            parent::__construct($text);
            $this->level = $level;
        }
    
        public function genContent()
        {
            $level = 'h1';
            if($this->level > 5)
                $level = 'h5';
            else if($this->level < 1)
                $level = 'h1';
            else
                $level = 'h' . $this->level;
            
            echo '<' . $level . '>';
            parent::genContent();
            echo '</' . $level . ">\n";
        }
    }
    
    class H1 extends HSnippet
    {
        public function __construct($text)
        {
            parent::__construct($text, 1);
        }
    }
    
    class H2 extends HSnippet
    {
        public function __construct($text)
        {
            parent::__construct($text, 2);
        }
    }
    
    class H3 extends HSnippet
    {
        public function __construct($text)
        {
            parent::__construct($text, 3);
        }
    }
    
    class H4 extends HSnippet
    {
        public function __construct($text)
        {
            parent::__construct($text, 4);
        }
    }
    
    class H5 extends HSnippet
    {
        public function __construct($text)
        {
            parent::__construct($text, 5);
        }
    }
?>
