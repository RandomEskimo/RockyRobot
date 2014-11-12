<?php
    
    $locator->inc("TableSnippet");
    
    class DebugSnippet extends Snippet
    {
        private static $info = array();
        private static $log_text = array();
        
        public static function addInfo($info_name, $info)
        {
            self::$info[$info_name] = $info;
        }
        
        public static function log($tag, $text)
        {
            self::$log_text[] = array($tag, $text);
        }
        
        public function genContent()
        {
            echo '<div class="debug" style="color:black;background-color:white;"><h3>Debug Info</h3>';
            if(!empty(self::$log_text))
            {
                echo '<h4>Log</h4>';
                $table = new TableSnippet();
                $table->addSnippet(new TextSnippet("Tag"));
                $table->addSnippet(new TextSnippet("Text"));
                $table->addRow();
                foreach(self::$log_text as $row)
                {
                    $table->addSnippet(new TextSnippet($row[0]));
                    $table->addSnippet(new TextSnippet($row[1]));
                    $table->addRow();
                }
                $table->genContent();
            }
            $keys = array_keys(self::$info);
            foreach($keys as $key)
            {
                $item = self::$info[$key];
                echo '<h4>' . $key . '</h4>';
                if(is_string($item))
                    echo $item;
                else if(is_subclass_of($item, 'Snippet'))
                    $item->genContent();
                else
                {
                    echo '<pre>';
                    print_r($item);
                    echo '</pre>';
                }
                    
            }
            echo '</div>';
        }
    }
?>
