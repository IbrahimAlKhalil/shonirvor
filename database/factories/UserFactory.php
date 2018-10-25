<?php

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'mobile' => '01'.$faker->randomElement([1, 5, 6, 7, 8, 9]).$faker->randomNumber(8, true),
        'email' => $faker->unique()->freeEmail,
        'nid' => $faker->randomNumber(9, true),
        'dob' => $faker->date(),
        'qualification' => $faker->randomElement(['SSC', 'HSC', 'BBA', 'MBA', '1 Year Experience', '2 Years Experience', '3 Years Experience']),
        'address' => $faker->address,
        'photo' => 'seed/user-photos/'.mt_rand(1, 190).'.jpg',
        'password' => '$2y$10$mBXIXfLULn4Vc7bJtVRk3.ZQ0S3Zb02x1xC/wmxsP.4H5TMGKIkHC', // 123456
        'remember_token' => str_random(10)
    ];
});