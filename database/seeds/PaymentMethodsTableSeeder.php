<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsTableSeeder extends Seeder
{
    public function run()
    {
        $rawData = [
            ['বিকাশ', '01950880465', 'পারসোনাল'],
            ['রকেট', '01589655795', 'এজেন্ট']
        ];

        $data = [];

        foreach ($rawData as $rawDatum) {
            array_push($data, [
                'name' => $rawDatum[0],
                'accountId' => $rawDatum[1],
                'account_type' => $rawDatum[2]
            ]);
        }

        DB::table('payment_methods')->insert($data);
    }
}
