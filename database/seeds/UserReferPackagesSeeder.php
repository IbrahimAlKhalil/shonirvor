<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserReferPackagesSeeder extends Seeder
{
    public function run()
    {
        $userIds = DB::table('users')
            ->inRandomOrder()
            ->take(50)
            ->pluck('id');

        $referPackageIds = DB::table('packages')
            ->where('package_type_id', 5)
            ->pluck('id')
            ->toArray();

        foreach ($userIds as $userId) {
            DB::table('user_refer_packages')->insert([
                [
                    'user_id' => $userId,
                    'package_id' => array_random($referPackageIds)
                ]
            ]);
        }
    }
}
