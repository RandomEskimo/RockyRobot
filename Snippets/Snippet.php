<?php

    class Snippet
    {
        private static $uses = array();
    
        public function genContent()
        {
            //include all needed files
            foreach(self::$uses as $file)
            {
                set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext)
                {
                    global $file;
                    echo "Problem including file: " . $file . ": [" . $errno . "] " . $errstr . ", in: " . $errfile . "[" . $errline . "]";
                    exit();
                });
                include_once $locator->find($file);
                restore_error_handler();
            }
            
        }
        
        public static function inc($file)
        {
            self::$uses[] = $file;
        }
    }
?>
