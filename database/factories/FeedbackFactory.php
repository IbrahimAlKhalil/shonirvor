<?php

use App\Models\Ind;
use App\Models\Org;
use App\Models\User;
use App\Models\Feedback;
use Faker\Generator as Faker;

$factory->define(Feedback::class, function (Faker $faker) {

    return [
        'user_id' => rand(1, 100),
        'feedbackable_id' => rand(1, 100),
        'feedbackable_type' => array_random(['ind', 'org']),
        'star' => mt_rand(1, 5),
        'say' => $faker->realText()
    ];
});