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
            echo '<div class="debug" style="color:black;background-color:white;"><h3>Debug Info</h3>';
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
