<?php

/**
 * @param $data string
 * @param $name string
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


function selectOpt($data1, $data2)
{
    if ($data1 == $data2) {
        return 'selected';
    }

    return '';
}

function checkBox($data)
{
    if (boolval($data)) {
        return 'checked';
    }

    return '';
}