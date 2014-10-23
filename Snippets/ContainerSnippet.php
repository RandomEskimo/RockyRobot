<?php

    class ContainerSnippet extends Snippet
    {
        private $snippets = array();
        
        public function addSnippet(Snippet $snippet)
        {
            $this->snippets[] = $snippet;
        }
        
        public function numSnippets()
        {
            return count($this->snippets);
        }
        
        public function getSnippet($index)
        {
            return $this->snippets[$index];
        }
        
        public function genAll()
        {
            for($i = 0;$i < count($this->snippets);++$i)
                $this->snippets[$i]->genContent();
        }
        
        public function genContent()
        {
            $this->genAll();
        }
    }
?>
