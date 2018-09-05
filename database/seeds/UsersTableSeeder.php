<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Dealer;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $customer = new User;
        $customer->name = "Hujaifa Islam";
        $customer->mobile = '00000000000';
        $customer->password = '$2y$10$mBXIXfLULn4Vc7bJtVRk3.ZQ0S3Zb02x1xC/wmxsP.4H5TMGKIkHC';
        $customer->save();

        $superadmin = new User;
        $superadmin->name = "Rafiq Uddin";
        $superadmin->mobile = '11111111111';
        $superadmin->password = '$2y$10$mBXIXfLULn4Vc7bJtVRk3.ZQ0S3Zb02x1xC/wmxsP.4H5TMGKIkHC';
        $superadmin->save();
        $superadmin->attachRole(1);

        $dealer = new User;
        $dealer->name = "Ikram Mansur";
        $dealer->mobile = '22222222222';
        $dealer->password = '$2y$10$mBXIXfLULn4Vc7bJtVRk3.ZQ0S3Zb02x1xC/wmxsP.4H5TMGKIkHC';
        $dealer->save();
        $dealer->attachRole(2);
        $dealer->dealer()->create();

        $customer = new User;
        $customer->name = "Hujaifa Islam";
        $customer->mobile = '44444444444';
        $customer->password = bcrypt('123456');
        $customer->save();

        factory(User::class, 80)->create()->each(function($user) {
            // Attach a role
            $countRoles = Role::count();
            $user->roles()->attach(rand(1, $countRoles));
            // If Dealer
            if ($user->hasRole('dealer'))
            {
                $user->dealer()->save(factory(Dealer::class)->make());
            }
        });
    }
}