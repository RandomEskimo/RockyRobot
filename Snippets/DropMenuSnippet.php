<?php
    include_once $locator->find("Snippet");

    class DropMenuItem extends Snippet
    {
        private $sub_items;
        private $label;
        private $link;
        
        public function __construct($label, $link)
        {
            $this->sub_items = array();
            $this->link = $link;
            $this->label = $label;
        }
        
        public function addMenuItem(DropMenuItem $item)
        {
            $this->sub_items[] = $item;
        }
        
        public function genContent()
        {
            echo '<li><a href="' . $this->link . '">' . $this->label . '</a>';
            if(!empty($this->sub_items))
            {
                echo '<ul>';
                foreach($this->sub_items as $item)
                    $item->genContent();
                echo '</ul>';
            }
            echo '</li>';
        }
    }

    class DropMenuSnippet extends Snippet
    {
        private $menu_items;
    
        public function __construct()
        {
            $this->menu_items = array();
        }
        
        public function addMenuItem(DropMenuItem $item)
        {
            $this->menu_items[] = $item;
        }
    
        public function genContent()
        {
            echo '<ul id="nav" class="drop">';
            foreach($this->menu_items as $item)
                $item->genContent();
            echo '</ul><br/><br/>';
        }
    }
?>
