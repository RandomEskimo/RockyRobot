<?php
    include_once $locator->find("ContainerSnippet");

    class FormSnippet extends ContainerSnippet
    {
        public static $METHOD_GET  = 0;
        public static $METHOD_POST = 1;
    
        private $method;
        private $action;
        private $binary;
    
        public function __construct($method, $action)
        {
            $this->action = $action;
            $this->method = $method;
            $this->binary = false;
        }
        
        public function genContent()
        {
            $method = 'post';
            if($this->method == FormSnippet::$METHOD_GET)
                $method = 'get';
            else if($this->method == FormSnippet::$METHOD_POST)
                $method = 'post';
            echo '<form action="' .$this->action . '" method="' . $method . '"';
            if($this->binary)
                echo ' enctype="multipart/form-data" ';
            echo ">\n";
            $this->genAll();
            echo "</form>\n";
        }
        
        public function setBinary($binary)
        {
            $this->binary = $binary;
        }
    }
?>
