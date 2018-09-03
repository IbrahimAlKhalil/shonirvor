<?php

use App\Models\Dealer;
use Faker\Generator as Faker;

$factory->define(Dealer::class, function (Faker $faker) {
    return [
        'mobile' => '01'.$faker->randomElement([1, 5, 6, 7, 8, 9]).$faker->randomNumber(8, true),
        'email' => $faker->unique()->freeEmail,
        'district' => $faker->state,
        'thana' => $faker->city,
        'union' => $faker->streetName,
        'address' => $faker->address
    ];
});
