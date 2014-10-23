<?php
    include_once $locator->find("Snippet");

    class TextAreaSnippet extends Snippet
    {
        private $name;
        private $value;
        private $rows;
        private $columns;
        
        public function __construct($name, $value, $rows, $columns)
        {
            $this->name = $name;
            $this->value = $value;
            $this->rows = $rows;
            $this->columns = $columns;
        }
    
        public function genContent()
        {
            echo "<textarea rows=\"" . $this->rows . "\" cols=\"" . $this->columns . "\" name=\"" . $this->name . "\">";
            if(!is_null($this->value) && $this->value != "")
                echo htmlentities($this->value);
            echo "</textarea>";
        }
    }
?>
