<?php

use App\Models\ContentType;
use Illuminate\Support\Facades\DB;

/**
 * Check if form old data exist
 *
 * @param $name string
 * @param $data string
 * @return string
 */
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
 */
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
 */
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

function indVisitorCount($indId)
{
    $visitor['today'] = DB::table('ind_visitor_counts')->where('ind_id', $indId)->whereDate('created_at', date('Y-m-d'))->sum('how_much');
    $visitor['thisMonth'] = DB::table('ind_visitor_counts')->where('ind_id', $indId)->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->sum('how_much');
    $visitor['thisYear'] = DB::table('ind_visitor_counts')->where('ind_id', $indId)->whereYear('created_at', date('Y'))->sum('how_much');

    return $visitor;
}

function orgVisitorCount($orgId)
{
    $visitor['today'] = DB::table('org_visitor_counts')->where('org_id', $orgId)->whereDate('created_at', date('Y-m-d'))->sum('how_much');
    $visitor['thisMonth'] = DB::table('org_visitor_counts')->where('org_id', $orgId)->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->sum('how_much');
    $visitor['thisYear'] = DB::table('org_visitor_counts')->where('org_id', $orgId)->whereYear('created_at', date('Y'))->sum('how_much');

    return $visitor;
}

function readableDays(int $days)
{
    $years = floor($days / 365);
    $months = floor(($days - ($years * 365))/30);
    $remainingDays = ($days - ($years * 365) - ($months * 30));

    $result = '';
    if ($years) $result .= en2bnNumber($years) . ' বছর ';
    if ($months) $result .= en2bnNumber($months) . ' মাস ';
    if ($remainingDays) $result .= en2bnNumber($remainingDays) . ' দিন';

    return $result;
}