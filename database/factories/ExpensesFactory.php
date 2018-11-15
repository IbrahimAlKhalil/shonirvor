<?php

use App\Models\Expense;
use Faker\Generator as Faker;

$factory->define(Expense::class, function (Faker $faker) {
    return [
        'user_id' => rand(1, 200),
        'payment_method_id' => '',
        'expense_type_id' => '',
        'amount' => '',
        'from' => rand(1000, 9999)
    ];
});
