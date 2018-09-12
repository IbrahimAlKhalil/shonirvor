<?php

use App\Models\PendingIndService;
use App\Models\PendingOrgService;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {

    $categoriables = [
        PendingIndService::class,
        PendingOrgService::class
    ];

    $categoriableType = $faker->randomElement($categoriables);
    $categoriable = factory($categoriableType)->create();

    return [
        //
    ];
});
