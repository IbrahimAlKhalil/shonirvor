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
function en2bnNumber($number)
{
    $bnNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
    $converted = str_replace(range(0, 9), $bnNumbers, $number);

    return $converted;
}

/**
 * @param $data1 mixed
 * @param $data2 mixed
 * @return string
 * */

function selectOpt($data1, $data2)
{
    if ($data1 == $data2) {
        return 'selected';
    }

    return '';
}

/**
 * @param $data integer|boolean
 * @return string
 * */

function checkBox($data)
{
    if (boolval($data)) {
        return 'checked';
    }

    return '';
}

/**
 * Returns array of random elements from given array
 *
 * @param $array array
 * @return mixed
 * */

function randomElement($array)
{
    return $array[array_rand($array, 1)];
}

/**
 * Returns 1 random element from given array
 *
 * @param $array array
 * @param $howMany integer
 * @return array
 * */

function randomElements($array, $howMany = 2)
{
    $keys = array_rand($array, $howMany);
    $returnArray = [];

    // make sure that $keys holds an array
    if (gettype($keys) != 'array') {
        $keys = [$keys];
    }

    foreach ($keys as $key) {
        $returnArray[$key] = $array[$key];
    }

    return $returnArray;
}