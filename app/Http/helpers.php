<?php

use GuzzleHttp\Client;
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

/**
 * Visitor counter for Ind service
 *
 * @param int $indId
 * @return array
 */
function indVisitorCount($indId)
{
    $visitor['today'] = DB::table('ind_visitor_counts')->where('ind_id', $indId)->whereDate('created_at', date('Y-m-d'))->sum('how_much');
    $visitor['thisMonth'] = DB::table('ind_visitor_counts')->where('ind_id', $indId)->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->sum('how_much');
    $visitor['thisYear'] = DB::table('ind_visitor_counts')->where('ind_id', $indId)->whereYear('created_at', date('Y'))->sum('how_much');

    return $visitor;
}

/**
 * Visitor counter for Org service
 *
 * @param int $orgId
 * @return array
 */
function orgVisitorCount($orgId)
{
    $visitor['today'] = DB::table('org_visitor_counts')->where('org_id', $orgId)->whereDate('created_at', date('Y-m-d'))->sum('how_much');
    $visitor['thisMonth'] = DB::table('org_visitor_counts')->where('org_id', $orgId)->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->sum('how_much');
    $visitor['thisYear'] = DB::table('org_visitor_counts')->where('org_id', $orgId)->whereYear('created_at', date('Y'))->sum('how_much');

    return $visitor;
}

/**
 * Convert days to day-month-year
 *
 * @param int $days
 * @return string
 */
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

/**
 * Send SMS
 *
 * @param string $mobile
 * @param string $message
 * @return array
 */
function sms($mobile, $message)
{
    if (strlen($mobile) != 11) {
        return [
            'success' => false,
            'status' => 'মোবাইল নাম্বারটি ১১ সংখ্যার নয়।'
        ];
    }

    if (empty($message)) {
        return [
            'success' => false,
            'status' => 'মেসেজে কিছু বলা হয়নি'
        ];
    }

    if (! env('SMS_ENABLED')) {
        return [
            'success' => false,
            'status' => 'মেসেজ সার্ভিসটি env তে বন্ধ আছে।'
        ];
    }

    $client = new Client([
        'base_uri' => 'http://portal.smsinbd.com'
    ]);

    $response = $client->request('GET','/smsapi', [
        'query' => [
            'api_key' => env('SMS_API_KEY'),
            'type' => 'text',
            'contacts' => $mobile,
            'senderid' => 'WIFAQ',
            'msg' => $message,
            'method' => 'api'
        ]
    ]);

    $status = strtolower(json_decode(trim($response->getBody()->getContents()))->status);

    if ($status == 'success') {
        return [
            'success' => true,
            'status' => 'মেসেজ পাঠানো হয়েছে।'
        ];
    } else {
        return [
            'success' => false,
            'status' => 'এসএমএস সার্ভারে সমস্যার কারণে মেসেজ পাঠানো সম্ভব হয়নি।'
        ];
    }
}