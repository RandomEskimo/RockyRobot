<?php
    include_once $locator->find("Snippet");

    class InputSnippet extends Snippet
    {
        private $name;
        private $value;
        private $type;
        
        public function __construct($name, $value, $type)
        {
            $this->name = $name;
            $this->value = $value;
            $this->type = $type;
        }
    
        public function genContent()
        {
            echo "<input type=\"" . $this->type . "\" name=\"" . $this->name . "\" ";
            if(!is_null($this->value) && $this->value != "")
                echo "value=\"" . $this->value . "\"";
            echo " />";
        }
    }
?>

