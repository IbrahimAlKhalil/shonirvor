<?php

use App\Models\Role;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {

        $newUser = new User;
        $newUser->name = "Customer";
        $newUser->mobile = '0565465411';
        $newUser->email = 'abc@abc.com';
        $newUser->age = '48';
        $newUser->address = '6626 Gregg Port
South Marquisfort, FL 56725';
        $newUser->nid = '6898653214654';
        $newUser->password = \Illuminate\Support\Facades\Hash::make('abcd');
        $newUser->save();


        factory(User::class, 80)->create()->each(function ($user) {
            $user->documents()->save(factory(UserDocument::class)->make());
            // Attach a role
            $countRoles = Role::count();
            $user->roles()->attach(rand(1, $countRoles));
        });
    }
}