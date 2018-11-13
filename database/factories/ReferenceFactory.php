<?php

use App\Models\Reference;
use Illuminate\Support\Facades\DB;

$factory->define(Reference::class, function () {

    return [
        'user_id' => rand(1, 200),
        'service_id' => rand(1, 200),
        'service_type_id' => rand(1, 2),
        'onetime_interest' => rand(5, 40),
        'renew_interest' => rand(5, 40),
        'duration' => rand(0, 1) ? rand(10, 300) : null,
        'target' => rand(0, 1) ? rand(5, 20) : null,
        'fail_onetime_interest' => rand(0, 1) ? rand(5, 40) : null,
        'fail_renew_interest' => rand(0, 1) ? rand(5, 40) : null
    ];
});
