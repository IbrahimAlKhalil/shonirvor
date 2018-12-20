<?php

use Faker\Generator as Faker;
use App\Models\OrgAdditionalPrice;

$factory->define(OrgAdditionalPrice::class, function (Faker $faker) {
    return [
        'org_id' => rand(1, 200),
        'name' => $faker->jobTitle,
        'info' => $faker->realText()
    ];
});
