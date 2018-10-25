<?php

use App\Models\ContentType;

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
 * @param $data integer|boolean
 * @return string
 */

function isActive($data)
{
    if (boolval($data)) {
        return 'active';
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
 * Get dynamic contents from database
 * Note: This function is intended only for the contentType witch can have multiple contents, such as slider
 *
 * @param $name string
 * @return mixed
 */
function getContents($name)
{
    return ContentType::where('name', $name)->with('contents')->first()->contents();
}


/**
 * Get dynamic contents from database
 *
 * @param $name string
 * @return string|null
 */
function getContent($name)
{
    return ContentType::where('name', $name)->with('contents')->first()->contents()->first();
}