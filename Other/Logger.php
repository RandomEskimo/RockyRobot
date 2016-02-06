<?php

class Logger
{
    private static $lines = array();
    
    public static function Log($text)
    {
        if(DEBUG)
        {
            self::$lines[] = $text;
        }
    }
    
    public static function CreateLog()
    {
        if(DEBUG && !empty(self::$lines))
        {
            $text  = "======================================\n";
            $text .= "Information\n";
            $text .= "======================================\n";
            $text .= "{info to be added}\n";
            $text .= "======================================\n";
            foreach(self::$lines as $line)
            {
                $text .= $line . "\n";
            }
            $file_name = $_SERVER['DOCUMENT_ROOT'] . "/Logging/" . date('Y-m-d_H:i:s') . "-" . $_SERVER['REMOTE_ADDR'] . ".log";
            file_put_contents($file_name, $text);
        }
    }
}

?>