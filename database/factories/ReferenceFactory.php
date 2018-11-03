<?php

use App\Models\Reference;
use Illuminate\Support\Facades\DB;

$factory->define(Reference::class, function () {

    $referrerPackageIds = DB::table('packages')
        ->where('package_type_id', 5)
        ->pluck('id')
        ->toArray();

    return [
        'user_id' => rand(1, 100),
        'service_id' => rand(1, 100),
        'service_type_id' => rand(1, 2),
        'package_id' => array_random($referrerPackageIds)
    ];
});
