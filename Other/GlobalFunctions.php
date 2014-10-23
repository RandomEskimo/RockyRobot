<?php

function shorten($text, $limit)
{
    $words = explode(" ", $text);
    $num_words = count($words);
    $out = "";
    $i = 0;
    for($i;$i < $num_words && $i < $limit;++$i)
        $out = $out . " " . $words[$i];
    if($i < $num_words)
        $out = $out . "...";
    return $out;
}

?>
