<?php

use App\Models\Ind;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Ind::class, function (Faker $faker) {

    $userMobiles = User::pluck('mobile');

    return [
        'email' => $faker->unique()->email,
        'mobile' => '01'
            . $faker->randomElement([1, 6, 7, 8, 9])
            . $faker->unique()->randomNumber(8),
        'referrer' => $faker->randomElement($userMobiles),
        'website' => 'https://www.sandofvega.com',
        'facebook' => 'https://facebook.com/sandofvega',
        'address' => $faker->address,
        'experience_certificate' => 'seed/ind/exp-cert.png',
        'cv' => 'seed/ind/cv.pdf',
        'is_pending' =>  mt_rand(0, 1),
        'is_top' =>  mt_rand(0, 1)
    ];
});
