<?php

use App\Models\Role;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        factory(User::class, 80)->create()->each(function($user) {
            $user->documents()->saveMany(factory(UserDocument::class, 2)->make());
            // Attach a role
            $countRoles = Role::count();
            $user->roles()->attach(rand(1, $countRoles));
        });
    }
}