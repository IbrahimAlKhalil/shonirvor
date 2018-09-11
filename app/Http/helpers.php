<?php

/**
 * Check if form old data exist
 *
 * @param $name string
 * @param $data string
 * @return string
 * */
function oldOrData($name, $data)
{
    $old = old($name);
    if ($old) {
        return $old;
    }

    return $data;
}

/**
 * Convert English numbers to Bangla numbers
 *
 * @param $number string
 * @return string
 */
function en2bnNumber ($number){
    $bnNumbers= array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
    $converted = str_replace(range(0, 9), $bnNumbers, $number);

    return $converted;
}