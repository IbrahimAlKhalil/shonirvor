<?php

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(App\Models\Ind::class, function (Faker $faker) {
    $userMobiles = User::pluck('mobile');

    return [
        'email' => $faker->unique()->email,
        'mobile' => '01'
            . $faker->randomElement([1, 6, 7, 8, 9])
            . $faker->unique()->randomNumber(8),
        'referrer' => $faker->randomElement($userMobiles),
        'website' => $faker->url,
        'facebook' => $faker->url,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'address' => $faker->address,
        'experience_certificate' => 'seed/ind/exp-cert.png',
        'is_pending' =>  rand(0, 1)
    ];
});