<?php

use App\Models\Org;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Org::class, function (Faker $faker) {

    $categoryIds = DB::table('categories')
        ->where('service_type_id', 2)
        ->pluck('id')
        ->toArray();

    return [
        'user_id' => rand(1, 200),
        'category_id' => array_random($categoryIds),
        'division_id' => rand(1, 8),
        'district_id' => rand(1, 64),
        'thana_id' => rand(1, 491),
        'union_id' => rand(1, 4541),
        'village_id' => rand(1, 1000),
        'email' => $faker->unique()->email,
        'mobile' => '01'
            . $faker->randomElement([1, 6, 7, 8, 9])
            . $faker->unique()->randomNumber(8),
        'name' => $faker->company,
        'description' => $faker->paragraph(5),
        'logo' => 'seed/org/logo/'.mt_rand(1, 13).'.png',
        'website' => 'http://'.$faker->domainName,
        'facebook' => 'https://facebook.com/'.$faker->userName,
        'address' => $faker->address,
        'expire' => rand(0, 1) ? $faker->dateTimeBetween('-3 years', '3 years') : null,
        'top_expire' => rand(0, 1) ? $faker->dateTimeBetween('-3 years', '3 years') : null,
        'trade_license' => 'seed/org/trade-license.png',
        'slug' => $faker->slug(rand(1, 6)),
        'deleted_at' => rand(0, 1) ? $faker->dateTime : null
    ];
});
