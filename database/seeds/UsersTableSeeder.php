<?php

use App\Models\Dealer;
use App\Models\IndCategory;
use App\Models\IndSubCategory;
use App\Models\User;
use App\Models\IndService;
use App\Models\OrgService;
use Illuminate\Database\Seeder;
use App\Models\PendingIndService;
use App\Models\PendingOrgService;

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

        factory(User::class, 20)->create()->each(function ($user) {

            // Attach a role
            $user->roles()->attach(rand(1, 2));
            // If Dealer
            if ($user->hasRole('dealer')) {
                $user->dealer()->save(factory(Dealer::class)->make());
            }

        });
    }
}