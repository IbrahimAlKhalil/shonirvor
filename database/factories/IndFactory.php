<?php

use App\Models\Ind;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Ind::class, function (Faker $faker) {

    $categoryIds = DB::table('categories')
        ->where('service_type_id', 1)
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
        'description' => $faker->unique()->paragraph(rand(5, 10)),
        'email' => $faker->unique()->email,
        'mobile' => '01'
            . $faker->randomElement([1, 6, 7, 8, 9])
            . $faker->unique()->randomNumber(8),
        'website' => 'http://'.$faker->domainName,
        'facebook' => 'https://facebook.com/'.$faker->userName,
        'address' => $faker->address,
        'experience_certificate' => 'seed/ind/exp-cert.png',
        'cv' => 'seed/ind/exp-cert.png',
        'status' => $faker->paragraph(rand(4, 15)),
        'expire' => rand(0, 1) ? $faker->dateTimeBetween('-3 years', '3 years') : null,
        'top_expire' => rand(0, 1) ? $faker->dateTimeBetween('-3 years', '3 years') : null,
        'is_available' => rand(0, 1),
        'deleted_at' => rand(0, 1) ? $faker->dateTime : null
    ];
});
