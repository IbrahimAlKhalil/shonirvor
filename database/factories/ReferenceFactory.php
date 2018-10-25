<?php

use App\Models\Reference;
use Illuminate\Support\Facades\DB;

$factory->define(Reference::class, function () {

    $lastUserId = DB::table('users')->count();

    $lastProviderId = $lastIndId = DB::table('inds')->count();
    $lastOrgId = DB::table('orgs')->count();
    if ($lastIndId < $lastOrgId) $lastProviderId = $lastOrgId;

    $referrerPackageIds = DB::table('packages')
        ->where('package_type_id', 5)
        ->pluck('id')
        ->toArray();

    return [
        'user_id' => rand(1, $lastUserId),
        'service_id' => rand(1, $lastProviderId),
        'service_type_id' => rand(1, 2),
        'package_id' => array_random($referrerPackageIds)
    ];
});
