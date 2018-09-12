<?php

use Faker\Generator as Faker;

$factory->define(App\Models\IndSubCategory::class, function (Faker $faker) {
    return [
        'sub_category' => $faker->unique()->jobTitle
    ];
});
