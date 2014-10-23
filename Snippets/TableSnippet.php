<?php
    include_once $locator->find("Snippet");

    class TableSnippet extends Snippet
    {
        private $snippets;
        private $row;
        
        public function __construct()
        {
            $this->snippets = array();
            $this->snippets[0] = array();
            $row = 0;
        }
        
        public function addSnippet(Snippet $snippet)
        {
            $this->snippets[$this->row][] = $snippet;
        }
        
        public function addEmptyCell()
        {
            $this->snippets[$this->row][] = null;
        }
        
        public function addRow()
        {
            $this->row++;
            $this->snippets[$this->row] = array();
        }
        
        public function genAll()
        {
            echo "<table>\n";
            foreach($this->snippets as $row)
            {
                if(empty($row))
                    continue;
                echo "    <tr>\n";
                foreach($row as $item)
                {
                    if($item == null)
                    {
                        echo "        <td></td>\n";
                    }
                    else
                    {
                        echo "        <td>";
                        echo $item->genContent();
                        echo "</td>\n";
                    }
                }
                echo "    </tr>\n";
            }
            echo "</table>\n";
        }
        
        public function genContent()
        {
            $this->genAll();
        }
    }
?>
