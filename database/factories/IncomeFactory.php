<?php

use App\Models\Income;
use Faker\Generator as Faker;

$factory->define(Income::class, function (Faker $faker) {
    return [
        'package_id' => rand(1, 20),
        'payment_method_id' => rand(1, 2),
        'incomeable_id' => rand(1, 200), // This may problem with AD
        'incomeable_type' => array_random(['ind', 'org', 'ad']),
        'from' => rand(1000, 9999),
        'transactionId' => $faker->regexify('[a-z0-9]{10}'),
        'approved' => rand(0, 1)
    ];
});
