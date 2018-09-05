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