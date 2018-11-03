<?php

use App\Models\Village;
use Faker\Generator as Faker;

$factory->define(Village::class, function (Faker $faker) {

    return [
        'bn_name' => $faker->firstName,
        'union_id' => rand(1, 876)
    ];
});
