<?php

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'mobile' => '01'.$faker->randomElement([1, 5, 6, 7, 8, 9]).$faker->randomNumber(8, true),
        'email' => $faker->unique()->safeEmail,
        'nid' => $faker->randomNumber(9, true),
        'age' => $faker->numberBetween(15, 70),
        'qualification' => $faker->randomElement(['SSC', 'HSC', 'BBA', 'MBA', '1 Year Experience', '2 Years Experience', '3 Years Experience']),
        'address' => $faker->address,
        'photo' => 'seed/user-photo/person.jpg',
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});