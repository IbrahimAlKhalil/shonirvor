<?php

use App\Models\Feedback;
use App\Models\Ind;
use App\Models\Org;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Feedback::class, function (Faker $faker) {

    $userIDs = User::pluck('id');

    if (Ind::all()->count() > Org::all()->count()) {
        $feedbackableId = Ind::pluck('id');
    } else {
        $feedbackableId = Org::pluck('id');
    }

    return [
        'user_id' => $faker->randomElement($userIDs),
        'feedbackable_id' => $faker->randomElement($feedbackableId),
        'feedbackable_type' => $faker->randomElement(['ind', 'org']),
        'star' => rand(0, 5),
        'say' => $faker->realText()
    ];
});