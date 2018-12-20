<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('payment_methods')->insert([
            [
                'name' => 'বিকাশ',
                'accountId' => '01950880465',
                'account_type' => 'পারসোনাল'
            ],
            [
                'name' => 'রকেট',
                'accountId' => '01589655795',
                'account_type' => 'এজেন্ট'
            ]
        ]);
    }
}
