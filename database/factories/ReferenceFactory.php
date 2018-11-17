<?php

use App\Models\Reference;
use Faker\Generator as Faker;

$factory->define(Reference::class, function (Faker $faker) {

    return [
        'user_id' => rand(1, 200),
        'service_id' => rand(1, 200),
        'service_type_id' => rand(1, 2),
        'onetime_interest' => rand(5, 40),
        'renew_interest' => rand(5, 40),
        'target' => rand(0, 1) ? rand(5, 20) : null,
        'target_start_time' => rand(0, 1) ? $faker->dateTimeBetween('-3 months', '1 months') : null,
        'target_end_time' => rand(0, 1) ? $faker->dateTimeBetween('-3 months', '1 months') : null,
        'fail_onetime_interest' => rand(0, 1) ? rand(5, 40) : null,
        'fail_renew_interest' => rand(0, 1) ? rand(5, 40) : null
    ];
});
