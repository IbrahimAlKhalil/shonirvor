<?php

use App\Models\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {

    return [
        'service_type_id' => rand(1, 2),
        'name' => $faker->jobTitle,
        'is_confirmed' => mt_rand(0, 1),
        'image' => 'seed/category-images/'.mt_rand(1, 4).'.png'
    ];
});