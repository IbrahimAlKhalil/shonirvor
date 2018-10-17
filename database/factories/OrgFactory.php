<?php

use App\Models\Org;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Org::class, function (Faker $faker) {

    $userMobiles = User::pluck('mobile');

    return [
        'email' => $faker->unique()->email,
        'mobile' => '01'
            . $faker->randomElement([1, 6, 7, 8, 9])
            . $faker->unique()->randomNumber(8),
        'referrer' => $faker->randomElement($userMobiles),
        'name' => $faker->company,
        'description' => $faker->paragraph(5),
        'logo' => 'seed/org/logo/' . rand(1, 13) . '.png',
        'website' => $faker->url,
        'facebook' => $faker->url,
        'address' => $faker->address,
        'trade_license' => 'seed/org/trade-license.png',
        'pricing_info' => $faker->paragraph(rand(4, 15)),
        'is_pending' => rand(0, 1),
        'is_top' => rand(0, 1)
    ];
});
