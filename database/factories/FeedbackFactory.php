<?php

use App\Models\Ind;
use App\Models\Org;
use App\Models\User;
use App\Models\Feedback;
use Faker\Generator as Faker;

$factory->define(Feedback::class, function (Faker $faker) {

    $userIDs = User::pluck('id')->toArray();

    if (Ind::all()->count() > Org::all()->count()) {
        $feedbackableId = Ind::pluck('id')->toArray();
    } else {
        $feedbackableId = Org::pluck('id')->toArray();
    }

    return [
        'user_id' => array_random($userIDs),
        'feedbackable_id' => array_random($feedbackableId),
        'feedbackable_type' => array_random(['ind', 'org']),
        'star' => mt_rand(1, 5),
        'say' => $faker->realText()
    ];
});