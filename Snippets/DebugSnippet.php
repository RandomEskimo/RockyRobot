<?php
    
    class DebugSnippet extends Snippet
    {
        private static $info = array();
        
        public static function addInfo($info_name, $info)
        {
            self::$info[$info_name] = $info;
        }
        
        public function genContent()
        {
            echo '<div class="debug" style="color:black;background-color:white;">';
            foreach(self::$info as $item)
            {
                if(is_string($item))
                    echo $item;
                else if(is_subclass_of($item, 'Snippet'))
                    echo $item
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
