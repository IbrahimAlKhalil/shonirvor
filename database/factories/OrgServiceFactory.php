<?php

use App\Models\OrgService;
use Faker\Generator as Faker;

$factory->define(OrgService::class, function (Faker $faker) {
    return [
        'org_name' => $faker->company,
        'mobile' => '01' . $faker->randomElement([1, 5, 6, 7, 8, 9]) . $faker->randomNumber(8, true),
        'description' => $faker->paragraph(5),
        'email' => $faker->companyEmail,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'address' => $faker->address
    ];
});
