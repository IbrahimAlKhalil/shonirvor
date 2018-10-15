<?php

use App\Models\Category;
use App\Models\ServiceType;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {

    $serviceTypeIds = ServiceType::pluck('id');

    return [
        'service_type_id' => $faker->randomElement($serviceTypeIds),
        'name' => $faker->jobTitle,
        'is_confirmed' => mt_rand(0, 1),
        'image' => 'seed/category-images/'.mt_rand(1, 4).'.png'
    ];
});