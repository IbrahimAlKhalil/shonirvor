<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $user = new User;
        $user->name = "Hujaifa Islam";
        $user->mobile = '0';
        $user->photo = 'seed/user-photos/'.rand(1, 190).'.jpg';
        $user->password = '$2y$10$mBXIXfLULn4Vc7bJtVRk3.ZQ0S3Zb02x1xC/wmxsP.4H5TMGKIkHC'; // 123456
        $user->save();

        factory(User::class, 10)->create();
    }
}