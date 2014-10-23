<?php
    include_once $locator->find("ContainerSnippet");

    class DivSnippet extends ContainerSnippet
    {
    
        private $id;
        private $_class;
        
        public function __construct($id, $class)
        {
            $this->id = $id;
            $this->_class = $class;
        }
        
        public function setId($id)
        {
            $this->id = $id;
        }
        
        public function getId()
        {
            return $this->id;
        }
        
        public function setClass($class)
        {
            $this->_class = $class;
        }
        
        public function getClass()
        {
            return $this->_class;
        }
    
        public function genContent()
        {
            echo '<div ';
            if(!is_null($this->id) && $this->id != "")
                echo 'id="' . $this->id . '" ';
            if(!is_null($this->_class) && $this->_class != "")
                echo 'class="' . $this->_class . '"';
            echo ">\n";
            $this->genAll();
            echo "</div>\n";
        }
    }
?>
