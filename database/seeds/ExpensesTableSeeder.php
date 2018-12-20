<?php

use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpensesTableSeeder extends Seeder
{
    public function run()
    {
        factory(Expense::class, 100)->create();
    }
}
