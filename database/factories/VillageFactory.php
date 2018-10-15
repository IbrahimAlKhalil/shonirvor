<?php

use App\Models\Village;
use Faker\Generator as Faker;

$factory->define(Village::class, function (Faker $faker) {

    $unionIds = [502, 503, 504];

    return [
        'bn_name' => $faker->firstName,
        'union_id' => $faker->randomElement($unionIds)
    ];
});
