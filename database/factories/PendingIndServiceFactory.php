<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\PendingIndService::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'mobile' => '01'.$faker->randomElement([1, 5, 6, 7, 8, 9]).$faker->randomNumber(8, true),
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'service' => $faker->jobTitle,
        'address' => $faker->address
    ];
});
