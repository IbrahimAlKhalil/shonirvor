<?php

use App\Models\Ind;
use App\Models\Org;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Package;
use App\Models\ContentType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
    $months = floor(($days - ($years * 365)) / 30);
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

    if (!config('sms.enabled')) {
        return [
            'success' => false,
            'status' => 'মেসেজ সার্ভিসটি বন্ধ আছে।'
        ];
    }

    $client = new Client([
        'base_uri' => 'http://portal.smsinbd.com'
    ]);

    $response = $client->request('GET', '/smsapi', [
        'query' => [
            'api_key' => config('sms.api'),
            'type' => 'unicode',
            'contacts' => $mobile,
            'senderid' => config('sms.senderid'),
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

/**
 * Get lifetime earn of a user
 *
 * @param User $user
 * @return int
 */
function userTotalEarn(User $user)
{
    $paymentPackageIds = Package::whereIn('package_type_id', [1, 2])->pluck('id')->toArray();

    $totalEarn = 0;
    $user->references->each(function ($reference) use (&$totalEarn, $user, $paymentPackageIds) {

        if (is_null($reference->service)) {
            $totalEarn += 0;
            return;
        }

        if (!($reference->target && $reference->target_start_time && $reference->target_end_time)) {
            $reference->service->payments()
                ->whereIn('package_id', $paymentPackageIds)
                ->where('approved', 1)
                ->get()->each(function ($payment, $key) use (&$totalEarn, $reference) {
                    $fee = $payment->package->properties
                        ->where('name', 'fee')->first()->value;

                    if (!$key) {
                        $totalEarn += $fee * $reference->onetime_interest / 100;
                    } else {
                        $totalEarn += $fee * $reference->renew_interest / 100;
                    }
                });
        } elseif ($reference->target_end_time->lt(now())) {
            $reference->service->payments()
                ->whereIn('package_id', $paymentPackageIds)
                ->where('approved', 1)
                ->get()->each(function ($payment, $key) use (&$totalEarn, $user, $reference) {
                    $howManyReferred = $user->references()->whereDate('created_at', '>', $reference->target_start_time)
                        ->whereDate('created_at', '<', $reference->target_end_time)->count();

                    $fee = $payment->package->properties
                        ->where('name', 'fee')->first()->value;

                    if ($howManyReferred >= $reference->target) {
                        if (!$key) {
                            $totalEarn += $fee * $reference->onetime_interest / 100;
                        } else {
                            $totalEarn += $fee * $reference->renew_interest / 100;
                        }
                    } else {
                        if (!$key) {
                            $totalEarn += $fee * $reference->fail_onetime_interest / 100;
                        } else {
                            $totalEarn += $fee * $reference->fail_renew_interest / 100;
                        }
                    }
                });
        }
    });

    return $totalEarn;
}

/**
 * Get user's current referrer package
 *
 * @param User $user
 * @return Package
 */
function userReferrerPackage(User $user)
{

    if ($user->referPackage()->exists()) {

        if ($user->referPackage->package->properties->where('name', 'duration')->first()->value
            && $user->referPackage->package->properties->where('name', 'refer_target')->first()->value) {

            if ($user->referPackage->created_at->addDays($user->referPackage->package->properties->where('name', 'duration')->first()->value)->gt(now())) {

                $package = $user->referPackage->package;

            } else {
                $package = Package::find(1);
            }

        } else {
            $package = $user->referPackage->package;
        }

    } else {
        $package = Package::find(1);
    }

    return $package;
}

/**
 * @param array $arr
 * @param array $rules
 */
function addValidationRules(array &$arr, array $rules)
{
    foreach ($rules as $parameter => $rule) {
        if (!!$rule[0]) {
            if (isset($arr[$parameter])) {
                $arr[$parameter] .= '|' . $rule[1];
                continue;
            }

            $arr[$parameter] = $rule[1];
        }
    }
}


/**
 * @param Ind $ind
 */

function deleteIndDocs(Ind $ind)
{
    if ($ind->experience_certificate) {
        Storage::delete($ind->experience_certificate);
    }

    if ($ind->cv) {
        Storage::delete($ind->cv);
    }

    if ($ind->cover_photo) {
        Storage::delete($ind->cover_photo);
    }

    $workImages = $ind->workImages;
    $ind->workImages()->delete();
    $ind->workMethods()->detach();
    $ind->subCategories()->where('is_confirmed', 1)->detach();
    $ind->subCategories()->where('is_confirmed', 0)->delete();
    $ind->slug()->delete();

    foreach ($workImages as $image) {
        Storage::delete($image->path);
    }

    $user = $ind->user;
    if ($user->inds()->count() == 0) {
        foreach ($ind->user->identities as $identity) {
            Storage::delete($identity->path);
        }
    }
}

/**
 * @param Org $org
 */
function deleteOrgDocs(Org $org)
{
    if ($org->trade_license) {
        Storage::delete($org->trade_license);
    }
    if ($org->logo) {
        Storage::delete($org->logo);
    }
    if ($org->cover_photo) {
        Storage::delete($org->cover_photo);
    }

    $workImages = $org->workImages;
    $org->workImages()->delete();
    $org->subCategoryRates()->detach();
    $org->subCategories()->where('is_confirmed', 1)->detach();
    $org->subCategories()->where('is_confirmed', 0)->delete();
    $org->slug()->delete();

    foreach ($workImages as $image) {
        Storage::delete($image->path);
    }

    $user = $org->user;
    if ($user->inds()->count() == 0) {
        foreach ($org->user->identities as $identity) {
            Storage::delete($identity->path);
        }
    }
}