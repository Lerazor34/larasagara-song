<?php

function can($urlName)
{
    return $urlName;
}

function limitString($string, $limit = 10)
{
    if (!is_string($string)) return $string;
    if (str_word_count($string, 0) > $limit) {
        $words = str_word_count($string, 2);
        $pos   = array_keys($words);
        $string  = '<span class="less">' . substr($string, 0, $pos[$limit]) . '... <a href="#">more</a></span><span class="more d-none">' . $string . '... <a href="#">less</a></span>';
    }
    return $string;
}
