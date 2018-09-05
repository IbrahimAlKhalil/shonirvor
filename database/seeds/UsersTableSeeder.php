<?php

use App\Models\IndService;
use App\Models\OrgService;
use App\Models\PendingIndService;
use App\Models\PendingOrgService;
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

        factory(User::class, 80)->create()->each(function ($user) {
            $user->documents()->save(factory(UserDocument::class)->make());

            $isIndService = rand(1, 10);
            $isOrgService = rand(1, 10);


            if ($isIndService >= 6) {
                $isPending = rand(1, 10) >= 6;

                switch ($isPending) {
                    case false:
                        $user->indService()->save(factory(IndService::class)->make());
                        $user->roles()->attach(3);
                        break;
                    default:
                        $user->pendingIndService()->save(factory(PendingIndService::class)->make());
                }
            }

            if ($isOrgService >= 6) {
                $isPending = rand(1, 10) >= 6;

                switch ($isPending) {
                    case false:
                        $iteration = rand(1, 5);

                        for ($i = 0; $i < $iteration; $i++) {
                            $user->orgService()->save(factory(OrgService::class)->make());
                        }

                        $user->roles()->attach(4);
                        break;
                    default:
                        $iteration = rand(1, 3);
                        for ($i = 0; $i < $iteration; $i++) {
                            $user->pendingOrgService()->save(factory(PendingOrgService::class)->make());
                        }
                }
            }


            // Attach a role
            $user->roles()->attach(rand(1, 2));
        });
    }
}