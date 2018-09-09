<?php

use Faker\Generator as Faker;

$factory->define(App\Models\OrgSubCategory::class, function (Faker $faker) {
    return [
        'category' => $faker->unique()->jobTitle
    ];
});
