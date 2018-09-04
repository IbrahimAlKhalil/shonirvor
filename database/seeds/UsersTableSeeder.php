<?php

use App\Models\Role;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $superadmin = new User;
        $superadmin->name = "Rafiq Uddin";
        $superadmin->mobile = '11111111111';
        $superadmin->password = bcrypt('123456');
        $superadmin->save();
        $superadmin->roles()->attach(1);

        $customer = new User;
        $customer->name = "Hujaifa Islam";
        $customer->mobile = '22222222222';
        $customer->password = bcrypt('123456');
        $customer->save();

        $dealer = new User;
        $dealer->name = "Ikram Mansur";
        $dealer->mobile = '33333333333';
        $dealer->password = bcrypt('123456');
        $dealer->save();
        $dealer->roles()->attach(2);

        $customer = new User;
        $customer->name = "Hujaifa Islam";
        $customer->mobile = '44444444444';
        $customer->password = bcrypt('123456');
        $customer->save();

        factory(User::class, 80)->create()->each(function($user) {
            $user->documents()->save(factory(UserDocument::class)->make());
            // Attach a role
            $countRoles = Role::count();
            $user->roles()->attach(rand(1, $countRoles));
        });
    }
}