<?php

use App\Models\UserDocument;
use Faker\Generator as Faker;

$factory->define(UserDocument::class, function (Faker $faker) {
    return [
        'document' => $faker->imageUrl(640, 480),
    ];
});
