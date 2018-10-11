<?php

use App\Models\SubCategory;
use Faker\Generator as Faker;

$factory->define(SubCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->jobTitle,
        'is_confirmed' => rand(0, 1)
    ];
});
