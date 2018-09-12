<?php

use App\Models\IndService;
use Faker\Generator as Faker;

$factory->define(IndService::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'mobile' => '01'.$faker->randomElement([1, 5, 6, 7, 8, 9]).$faker->randomNumber(8, true),
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'address' => $faker->address
    ];
});
