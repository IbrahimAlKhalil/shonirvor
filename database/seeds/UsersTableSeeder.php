<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $customer = new User;
        $customer->name = "Hujaifa Islam";
        $customer->mobile = '0';
        $customer->password = '$2y$10$mBXIXfLULn4Vc7bJtVRk3.ZQ0S3Zb02x1xC/wmxsP.4H5TMGKIkHC'; // 123456
        $customer->save();

        factory(User::class, 10)->create();
    }
}