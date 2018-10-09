<?php

use Faker\Generator as Faker;

$factory->define(App\Models\SubCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->jobTitle,
        'is_confirmed' => rand(0, 1)
    ];
});
